var isFormValid = false;

function validateForm() {
    var username = document.getElementById("username").value;
    var nom = document.getElementById("nom").value;
    var prenom = document.getElementById("prenom").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    isFormValid = true;

    if (username == "" || nom == "" || prenom == "" || email == "" || password == "" || confirmPassword == "") {
        document.getElementById("errorMessage").innerText = "Erreur : Veuillez remplir tous les champs.";
        document.getElementById("errorMessage").style.display = "block";
        isFormValid = false;
    }

    if (password !== confirmPassword) {
        document.getElementById("errorMessage").innerText = "Erreur : Les mots de passe ne correspondent pas.";
        document.getElementById("errorMessage").style.display = "block";
        isFormValid = false;
    }

    return isFormValid;
}

function checkUsername() {
    var username = document.getElementById("username").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/check_username.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            if (response === "Ce nom d'utilisateur est déjà utilisé.") {
                document.getElementById("usernameError").innerText = response;
                document.getElementById("usernameError").style.display = "block";
                isFormValid = false;
            } else {
                document.getElementById("usernameError").innerText = "";
                document.getElementById("usernameError").style.display = "none";
            }
        }
    };
    xhr.send("username=" + username);
}

function checkPasswordMatch() {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;
    var passwordMatchError = document.getElementById("passwordMatchError");

    if (password !== confirmPassword) {
        passwordMatchError.innerText = "Les mots de passe ne correspondent pas.";
        passwordMatchError.style.display = "block";
    } else {
        passwordMatchError.innerText = "";
        passwordMatchError.style.display = "none";
    }
}


