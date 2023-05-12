

document.addEventListener("DOMContentLoaded", function () {
  /* Script du burger menu animé */
  const content = document.getElementById("content");
  console.log(content)
  if (content.scrollHeight <= content.clientHeight) {
    content.style.overflowY = "hidden";
  }
  const burgerMenu = document.querySelector('.burger-menu');
  const menu = document.querySelector('.menu');

  burgerMenu.addEventListener('click', () => {
    burgerMenu.classList.toggle('active');
    menu.classList.toggle('active');
  });

  menu.addEventListener('click', () => {
    burgerMenu.classList.remove('active');
    menu.classList.remove('active');
  });

  /* Script pour afficher le modal de verification avant envoie de formulaire */
  const btnChecker = document.querySelector('#form-checker');
  const modalChecker = document.querySelector(".modal-calcul")
  if (btnChecker) {
    btnChecker.addEventListener('click', () => {
      modalChecker.classList.toggle('active');
      btnChecker.classList.toggle('active');

    });
  }



  /* Script Caché Home Console */
  const commands = [
    "Salut ! C'est Charles, je codais le formulaire de contact, et j'ai eu envie de savoir comment envoyer un email via smtp depuis un bash linux. J'ai trouvé la réponse, je te fais un condensé ;) ",
    "Tuto : Envoie d'un email via un script shell, sur linux en utilisant le serveur smtp de Gmail",
    "sudo apt-get update",
    "sudo apt-get install msmtp msmtp-mta",
    "touch ~/.msmtprc",
    "echo 'defaults' >> ~/.msmtprc",
    "echo 'auth on' >> ~/.msmtprc",
    "echo 'tls on' >> ~/.msmtprc",
    "echo 'tls_trust_file /etc/ssl/certs/ca-certificates.crt' >> ~/.msmtprc",
    "echo 'logfile ~/.msmtp.log' >> ~/.msmtprc",
    "echo 'account gmail' >> ~/.msmtprc",
    "echo 'host smtp.gmail.com' >> ~/.msmtprc",
    "echo 'port 587' >> ~/.msmtprc",
    "echo 'from your-email@gmail.com' >> ~/.msmtprc",
    "echo 'user your-email@gmail.com' >> ~/.msmtprc",
    "echo 'password your-app-password' >> ~/.msmtprc",
    "echo 'account default : gmail' >> ~/.msmtprc",
    "chmod 600 ~/.msmtprc",
    "touch send_email.sh",
    "echo '#!/bin/bash' >> send_email.sh",
    "echo 'subject=\"Sujet de l\\'email\"' >> send_email.sh",
    "echo 'to=\"recipient-email@example.com\"' >> send_email.sh",
    "echo 'body=\"Ceci est le corps de l\\'email.\"' >> send_email.sh",
    "echo 'from=\"your-email@gmail.com\"' >> send_email.sh",
    "echo 'echo -e \"Subject: $subject\\n\\n$body\" | msmtp -a gmail --from=\"$from\" \"$to\"' >> send_email.sh",
    "chmod +x send_email.sh",
    "./send_email.sh",
    "Félicitations !"
  ];

  const responses = [
    "Mise en place du contexte...",
    "Initialisation de la console...",
    "Mise à jour des paquets...",
    "Installation de msmtp et msmtp-mta...",
    "Création du fichier de configuration .msmtprc...",
    "Ajout de la configuration 'defaults'...",
    "Ajout de la configuration 'auth'...",
    "Ajout de la configuration 'tls'...",
    "Ajout de l'emplacement des certificats...",
    "Ajout de la configuration des logs...",
    "Ajout de la configuration du service via Gmail...",
    "Ajout de la configuration DNS de Gmail...",
    "Ajout de la configuration du port...",
    "Ajout de l'adresse email...",
    "Ajout de l'adresse email de l'utilisateur...",
    "Ajout de la clé privée...",
    "Ajout de la configuration par défaut sur Gmail...",
    "Modification des permissions du fichier .msmtprc...",
    "Création du fichier de script, send_email.sh...",
    "Ajout de la première ligne du script...",
    "Ajout de la ligne 'subject'...",
    "Ajout de la ligne 'to'...",
    "Ajout de la ligne 'body'...",
    "Ajout de la ligne 'from'...",
    "Ajout de la commande d'envoi d'email...",
    "Rendre le script exécutable...",
    "Envoi de l'email...",
    "Tu es maintenant un vrai script kiddie"
  ]


  const command = document.getElementById("command");
  const cursor = document.getElementById("cursor");
  let isRunning = false;

  function type(commands, responses, index) {

    // Vérifier si la fonction est déjà en cours d'exécution
    if (isRunning) {
      return;
    }
    isRunning = true;

    let i = 0;

    function print() {
      if (i < commands[index].length) {
        var console = document.querySelector(".console");
        console.scrollTop = console.scrollHeight;
        command.innerHTML += commands[index].charAt(i);
        i++;
        setTimeout(print, 35);
      } else {
        cursor.style.display = "none";
        command.innerHTML += "<br/><div>" + responses[index] + "</div><br/>";
        var console = document.querySelector(".console");
        console.scrollTop = console.scrollHeight;

        setTimeout(function () {
          cursor.style.display = "inline";
          type(commands, responses, index + 1);
        }, 500);

      }
    }

    print();
    isRunning = false;

  }

  function moveCursor() {
    // Obtenir la position actuelle de la souris
    let mouseX = event.clientX;
    let mouseY = event.clientY;

    // Déplacer la souris d'un pixel vers la droite
    mouseX += 1;

    // Créer un nouvel événement de souris avec la position modifiée
    const event = new MouseEvent('mousemove', {
      clientX: mouseX,
      clientY: mouseY
    });

    // Déclencher l'événement pour simuler le mouvement de la souris
    document.dispatchEvent(event);
  }


  /* Script pour lancer le script caché précédent */
  let imageContactBg = document.querySelector('.image-contact-bg');
  let secretButton = document.querySelector('.secret');

  if (imageContactBg) {
    imageContactBg.addEventListener('mouseenter', function () {
      secretButton.style.transitionDelay = "2s";
      secretButton.classList.add('active');
      setTimeout(function () {
        secretButton.classList.add('hover');
        // script pour décaler la souris de l'utilisateur afin de refresh le cursor pointer
        () => moveCursor();
        secretButton.addEventListener('click', function () {
          setTimeout(function () {
            var console = document.querySelector(".console");
            console.classList.add('active');
            imageContactBg.style.display = "none";
            type(commands, responses, 0);
          }, 500);
        });
      }, 2000);
    });
    imageContactBg.addEventListener('mouseleave', function () {
      secretButton.style.transitionDelay = "0s";
      secretButton.classList.remove('active');
      secretButton.classList.remove('hover');

    });
  }
  if (secretButton) {
    secretButton.addEventListener('mouseleave', function () {
      secretButton.style.transitionDelay = "0s";
      secretButton.classList.remove('active');
      secretButton.classList.remove('hover');

    });
  }

  const carrouselContainer = document.querySelector('.carrousel-container');

  if (carrouselContainer) {
    let position = 0;
    const articles = document.getElementsByClassName('article');
    const carrouselInner = document.getElementsByClassName('carrousel-inner')[0];

    const arrowLeft = document.getElementById('arrow-left');
    const arrowRight = document.getElementById('arrow-right');

    function updateArrowsVisibility() {
      const maxPosition = getTotalWidth() - carrouselInner.offsetWidth;

      arrowLeft.style.visibility = position === 0 ? 'hidden' : 'visible';
      arrowRight.style.visibility = position >= maxPosition - 20 ? 'hidden' : 'visible';
    }

    function resetPosition() {
      position = 0;
      carrouselInner.style.transform = `translateX(0)`;
      updateArrowsVisibility();
    }

    function getTotalWidth() {
      let totalWidth = 0;
      for (let i = 0; i < articles.length; i++) {
        totalWidth += articles[i].offsetWidth + 20;
      }
      return totalWidth;
    }

    function move(direction) {
      const articleWidth = articles[0].offsetWidth + 20;
      const maxPosition = getTotalWidth() - carrouselInner.offsetWidth;

      position += direction * articleWidth;
      position = Math.max(0, Math.min(position, maxPosition));

      carrouselInner.style.transform = `translateX(-${position}px)`;

      updateArrowsVisibility();
    }

    arrowLeft.addEventListener('click', function () {
      move(-1);
    });

    arrowRight.addEventListener('click', function () {
      move(1);
    });

    window.addEventListener('resize', function () {
      resetPosition();
    });

    updateArrowsVisibility();

  }

  // Trigger animation hover post list
  var elements = document.querySelectorAll(".article");
  var articleContent = document.querySelectorAll(".article-content-box");

  for (var i = 0; i < elements.length; i++) {
    (function (index) {
      elements[index].addEventListener("mouseover", function () {
        articleContent[index].style.width = "100%";
      });
      elements[index].addEventListener("mouseout", function () {
        articleContent[index].style.width = "0%";
      });
    })(i);
  }


  // Sélectionne la div avec la classe single-post-wrapper
  const homeChecker = document.querySelector(".hero");

  // Vérifie si la div existe avant d'exécuter le reste du script
  if (!homeChecker) {
    // Sélectionne le premier élément "p" dans la nav avec la classe navbar
    const navbarParagraph = document.querySelector("nav.navbar p:first-of-type");
    if (navbarParagraph) {
      navbarParagraph.textContent = "";
    }
    // Vérifie si l'élément existe avant de modifier son contenu
    if (navbarParagraph) {
      // Crée un nouveau lien
      const newLink = document.createElement("a");
      newLink.textContent = "Retour";
      newLink.href = "#";
      newLink.classList.add("arrow-left");
      newLink.classList.add("arrow");
      newLink.classList.add("arrow-back");
      newLink.onclick = function () {
        history.back();
        return false;
      }

      // Ajoute le lien comme enfant de l'élément "p"
      navbarParagraph.insertBefore(newLink, navbarParagraph.firstChild);
    }
  }

  const singlePostCommentWrapper = document.querySelector('.single-post-comment-wrapper');

  if (singlePostCommentWrapper) {
    let isExpanded = false;
    const commentHeader = singlePostCommentWrapper.querySelector('h2');
  
    commentHeader.addEventListener('click', () => {
      if (isExpanded) {
        singlePostCommentWrapper.style.height = '70px';
        isExpanded = false;
      } else {
        singlePostCommentWrapper.style.height = 'auto';
        isExpanded = true;
        const commentAnchor = document.querySelector('#ancre-comment');
        if (commentAnchor) {
          commentAnchor.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  }
  



})











