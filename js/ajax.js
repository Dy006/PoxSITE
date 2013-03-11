function signin(pseudo, email, password1, password2, pays) {
    var OAjax;
    if (window.XMLHttpRequest) OAjax = new XMLHttpRequest();
    else if (window.ActiveXObject) OAjax = new ActiveXObject('Microsoft.XMLHTTP');
    OAjax.open('POST', "bdd.php?a=registration", true);
    OAjax.onreadystatechange = function ()
    {
        if (OAjax.readyState == 4 && OAjax.status == 200)
        {
            if (document.getElementById)
            {
                if (OAjax.responseText == 'true')
                {
                    document.getElementById('alert_register').innerHTML = '<div class="alert alert-success"><h4>Information</h4>Votre compte utilisateur a bien été crée. Vous allez recevoir un mail qui vous donnera le lien et le code pour activer votre compte.</div>';

                    document.getElementById('buttonregister').classList.add('disabled');
                }
                else
                {
                    document.getElementById('alert_register').innerHTML = OAjax.responseText;
                }
            }
        }
    }

    OAjax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    OAjax.send('pseudo=' + pseudo + '&email=' + email + '&password1=' + password1 + '&password2=' + password2 + '&pays=' + pays);
}

function Co(pseudo, pass)
{
    var OAjax;
    if (window.XMLHttpRequest) OAjax = new XMLHttpRequest();
    else if (window.ActiveXObject) OAjax = new ActiveXObject('Microsoft.XMLHTTP');
    OAjax.open('POST', "./bdd.php?a=co", true);
    OAjax.onreadystatechange = function ()
    {
        if (OAjax.readyState == 4 && OAjax.status == 200)
        {
            if (document.getElementById)
            {
                if (OAjax.responseText == 'true')
                {
                    window.top.window.location.href = "account.php";
                }
                else if(OAjax.responseText == 'registerconfirm')
                {
                    window.top.window.location.href = "register.php?confirm=ok";
                }
                else
                {
                    document.getElementById('alert').innerHTML = OAjax.responseText;
                }
            }
        }
    }

    OAjax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    OAjax.send('pseudo=' + pseudo + '&password=' + pass);
}

function save_profile(password1,password2,email,age,country,firstname,secondname,msg_perso)
{
    var OAjax;
    if (window.XMLHttpRequest) OAjax = new XMLHttpRequest();
    else if (window.ActiveXObject) OAjax = new ActiveXObject('Microsoft.XMLHTTP');
    OAjax.open('POST', "./bdd.php?a=saveprofile", true);
    OAjax.onreadystatechange = function () {
        if (OAjax.readyState == 4 && OAjax.status == 200) {
            if (document.getElementById) {
                if (OAjax.responseText == 'true') {
                    document.getElementById('alert').innerHTML = '<div class="alert alert-info"><button type="button" class="close" data-dismiss="alert">×</button><h4>Information</h4>Les informations ont bien été modifiés.</div>';
                } else {
                    document.getElementById('alert').innerHTML = OAjax.responseText;
                }
            }
        }
    }

    OAjax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    OAjax.send('password1=' + password1 + '&password2=' + password2 + '&email=' + email + '&age=' + age + '&country=' + country + '&firstname=' + firstname + '&secondname=' + secondname + '&msg_perso=' + msg_perso);
}

function Reset_password(pseudo,password1,password2)
{
    var OAjax;
    if (window.XMLHttpRequest) OAjax = new XMLHttpRequest();
    else if (window.ActiveXObject) OAjax = new ActiveXObject('Microsoft.XMLHTTP');
    OAjax.open('POST', "./bdd.php?a=resetpassword", true);
    OAjax.onreadystatechange = function ()
    {
        if (OAjax.readyState == 4 && OAjax.status == 200)
        {
            if (document.getElementById)
            {
                if (OAjax.responseText == 'true')
                {
                    document.getElementById('alert').innerHTML = '<div class="alert alert-success"><h4>Information</h4>Votre mot de passe a bien été réinitialisé.</div>';

                    window.top.window.location.href = "account.php";
                }
                else
                {
                    document.getElementById('alert').innerHTML = OAjax.responseText;
                }
            }
        }
    }

    OAjax.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    OAjax.send('pseudo=' + pseudo + '&password1=' + password1 + '&password2=' + password2);
}
