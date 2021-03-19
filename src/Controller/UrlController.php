<?php

namespace App\Controller;

use App\Entity\Url;
use App\Entity\UrlStats;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UrlController extends AbstractController
{

    /**
     * @Route("/url/create", name="url_create")
     */
    public function create(Request $request, ValidatorInterface $validator, UserInterface $user = null): JsonResponse
    {
        $url = $request->get('url');
        $shortUrl = null;

        $violations = $validator->validate($url, [new Assert\Url(), new Assert\NotBlank()]);
        $errorMessages = [];

        if (count($violations)===0){
            # generate 5 digit hash
            $alpha_numeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            $url_hash = substr( str_shuffle($alpha_numeric),0,5);

            $em = $this->getDoctrine()->getManager();

            $url_item = new Url();
            $url_item->setUrl($url)
                ->setUrlHash( $url_hash )
                ->setCreatedAt( (new \DateTime()) )
                ->setUser($user ? $user : null)
                ->setClickCount(0)
                ->setIsPublic(true)
                ->setExpiredAt(( new \DateTime() ))
                ->setIsActive(true);

            $em->persist($url_item);
            $em->flush();

            $shortUrl = 'http://127.0.0.1:8000/'.$url_hash;
        }else{
            $accessor = PropertyAccess::createPropertyAccessor();
            foreach($violations as $v){
                $accessor->setValue($errorMessages, $v->getPropertyPath(), $v->getMessage());
            }
        }

        return new JsonResponse([
            'success'=>count($violations)===0??false,
            'response'=>$shortUrl,
            'error'=>count($violations)>0??false,
            'errorMessage'=>count($violations)>0?$errorMessages:null
        ],200);
    }

    /**
     * @Route("/{urlHash}", name="redirector")
     */
    public function redirector($urlHash, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $urlRepository = $em->getRepository(Url::class);

        $url_item = $urlRepository->findOneBy([
            'is_active'=>true,
            'urlHash'=>$urlHash
        ]);

        if ($url_item){
            $url = $url_item->getUrl();
            $urlId = $url_item->getId();

            $this->saveStats($urlId, $request);

            $url_item->setClickCount($url_item->getClickCount()+1);
            $em->flush();

            return $this->redirect($url);
        }

        return $this->redirectToRoute('index');
    }

    public function saveStats($urlId, Request $request)
    {
        $userAgent = $request->headers->get('User-Agent');
        $clientIp = $request->getClientIp();

        $em = $this->getDoctrine()->getManager();
        $locationData = json_decode(file_get_contents("http://ipinfo.io/{$clientIp}"));

        $url_stats = new UrlStats();
        $url_stats->setUrlId($urlId)
            ->setBrowser($userAgent)
            ->setIpAddress($clientIp)
            ->setDevice($this->isMobileDevice($userAgent)?'mobile':'desktop')
            ->setResolution(null)
            ->setLang(explode('-',$request->headers->get('Accept-Language'))[0]??null)
            ->setLocale($locationData->loc??null)
            ->setCity($locationData->city??null)
            ->setCountry($locationData->country??null)
            ->setCreatedAt( ( new \DateTime() ));

        $em->persist($url_stats);
        $em->flush();
    }

    private function isMobileDevice($userAgent) {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i"
            , $userAgent);
    }
}
