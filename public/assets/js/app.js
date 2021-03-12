function createShortUrl(){
    const urlInp = document.getElementById( 'urlinput');
    const  formError = document.getElementsByClassName('form-error')[0];

    formError.innerHTML = '';
    formError.classList.add()

    if (urlInp.value.length>0){
        postUrl();
    }else{
        formError.innerHTML = 'Bu alan boş bırakılamaz';
    }
    return false;
}

function postUrl(){
    const urlInp = document.getElementById('urlinput');
    console.log(urlInp.value);
    const formData = new FormData();

    const formError = document.getElementsByClassName('form-error')[0];
    const formSuccess = document.getElementsByClassName('form-success')[0];

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
}