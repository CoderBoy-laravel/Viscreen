    let show = false;
    let show1 = false;
    const modal1 = document.getElementById('modalDelete');
    const modal = document.getElementById('modal');
    const input = document.getElementsByTagName('input');
    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const modalDelHandler = (element) => {
        if (show1) {
            show1 = false;
            modal1.style.display = 'none'
        }else{
            const deletUserLink = document.getElementById('deletUserLink');
            deletUserLink.setAttribute('href', element.getAttribute('data-url'))
            show1 = true;
            modal1.style.display = 'flex'
        }
    }
    const modalHandler = () => {
        if (show) {
            show = false;
            input['email'].value = ''
            input['name'].value = ''
            input['password'].value = ''
            nameError.innerText = ''
            emailError.innerText = ''
            passwordError.innerText = ''
            modal.style.display = 'none'
        }else{
            show = true;
            modal.style.display = 'flex'
        }
    }
    const validateForm = () => {
        let email = true;
        let name = true;
        let password = true;
        if (input['name'].value == "") {
            name = false
            nameError.innerText = 'Please Provide Name'
        }
        else{
            name = true
            nameError.innerText = ''
        }
        if (!input['email'].value.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
            email = false
            emailError.innerText = 'Please Provide Valid Email'
        }
        else{
            email = true
            emailError.innerText = ''
        }
        if (input['password'].value == "") {
            password = false
            passwordError.innerText = 'Please Provide Password'
        }
        else{
            password = true
            passwordError.innerText = ''
        }
        if (email && name && password) {
            return true;
        }
        else{
            return false;
        }
    }
