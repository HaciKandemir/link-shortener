const shortenButton = document.getElementById('shortenButton');
const urlInp = document.getElementById('urlInput');
const formAlert = document.getElementById('shorten-alert');
const formAlertSpan = formAlert.getElementsByTagName('span')[0];

shortenButton.addEventListener('click',function(){
    if (shortenButton.classList.contains('btn-success')){
        urlInp.select();
        urlInp.setSelectionRange(0, 99999);
        document.execCommand("copy");
        shortenButton.innerHTML = 'Copied !';
    }
});

function createShortUrl() {
    if (!shortenButton.classList.contains('btn-success')){
        formAlertSpan.innerHTML = '';
        formAlert.classList.add('d-none');

        if (urlInp.value.trim().length > 0) {
            postUrl();
        } else {
            formAlertSpan.innerHTML = 'Bu alana boş bırakılamaz';
            formAlert.classList.remove('d-none');
        }
    }
    return false;
}

function postUrl(){
    const formData = new FormData();

    formData.append('url',urlInp.value);

    fetch('/url/create', {
        method: 'POST',
        body : formData
    }).then( resp => resp.json() )
        .then( resp => {

            if (resp.error === true){
                formAlertSpan.innerHTML = resp.errorMessage.url;
                formAlert.classList.remove('d-none');
            } else {
                urlInp.value = resp.response;
                //urlInp.classList.add('success');

                shortenButton.innerHTML = 'Copy';
                shortenButton.classList.add('btn-success');
            }
        } )
        .catch(err=>{
            console.log(err);
        })
}






/*const formSuccess = document.getElementsByClassName('form-success')[0];
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
    }*/
