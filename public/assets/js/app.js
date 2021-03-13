
    const formSuccess = document.getElementsByClassName('form-success')[0];
    const formResultDiv = $( ".alert:first" );

    function createShortUrl(){
        urlInp = document.getElementById( 'urlinput');

        if (urlInp.value.trim().length>0){
            console.log(urlInp.value);
        }else{
            formResultDiv.removeClass('d-none');
            formResultDiv.getElementsByTagName('span')[0].innerHTML="Bu alan boş bırakılamaz";
        }

        return false;
    }





/*function createShortUrl(){
    //formError.innerHTML = '';
    //formError.classList.add();

    if (urlInp.value!==null){
        console.log(urlInp.value);
    }

    if (urlInp.value.length>0){
        postUrl();
    }else{
        formError.innerHTML = 'Bu alan boş bırakılamaz';
    }
    return false;
}

function postUrl(){
    const formData = new FormData();

    formData.append('url',urlInp.value);

    fetch('/url/create', {
        method: 'POST',
        body: formData
    }).then ( resp => resp.json() )
        .then( resp => {
            if (resp.error === true){
                formError.innerHTML = resp.errorMessage;
            } else {
                formSuccess.innerHTML = 'url başarıyla oluşturuldu';
                formSuccess.classList.add('show');
                urlInp.value = '';
            }
        } )
        .catch( err => {
          console.log(err);
        } )
}*/