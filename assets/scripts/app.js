document.addEventListener("DOMContentLoaded", function () {
  /* Script du burger menu animé */
  // Récupération de l'élément avec l'id "content"
  const content = document.getElementById("content");
  // Vérification de la hauteur du contenu et masquage de la barre de défilement si nécessaire
  if (content.scrollHeight <= content.clientHeight) {
    content.style.overflowY = "hidden";
  }

  // Récupération des éléments du burger menu et du menu
  const burgerMenu = document.querySelector('.burger-menu');

  const menu = document.querySelector('.menu');

  // Ajout d'un écouteur d'événement sur le burger menu pour activer/désactiver la classe 'active'
  burgerMenu.addEventListener('click', () => {
    burgerMenu.classList.toggle('active');
    menu.classList.toggle('active');
  });

  // Vérification de l'URL pour la page "/post/id"
  const postPath = window.location.pathname.split("/post/")[1];

  // Vérifier s'il y a un nombre après "/post/"
  const regexNumber = /^\d+$/; // Expression régulière pour vérifier si c'est un nombre
  if (postPath && regexNumber.test(postPath)) {
    // Récupération du texte de l'élément avec l'id "post-text"
    var texteElement = document.getElementById("post-text");
    var texte = texteElement.textContent;
    // Remplacement de tous les tirets "-" par "<br>-"
    var newTexte = texte.replace(/-/g, "<br>-");
    texteElement.innerHTML = newTexte;
  }

  /* Script pour afficher le modal de vérification avant envoi de formulaire */
  // Récupération du bouton avec l'id "form-checker" et du modal avec la classe "modal-calcul"
  const btnChecker = document.querySelector('#form-checker');
  const modalChecker = document.querySelector(".modal-calcul")
  if (btnChecker) {
    // Ajout d'un écouteur d'événement sur le bouton pour activer/désactiver la classe 'active' du modal
    btnChecker.addEventListener('click', () => {
      modalChecker.classList.toggle('active');
      btnChecker.classList.toggle('active');

    });
  }




  /* Script Caché Home Console */
  // Tableaux de commandes et de réponses
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

  // Récupération des éléments de la console
  const command = document.getElementById("command");
  const cursor = document.getElementById("cursor");
  let isRunning = false;

  // Fonction pour afficher les commandes et réponses une par une
  function type(commands, responses, index) {

    // Initialisation d'une variable pour parcourir les caractères des commandes
    let i = 0;

    // Fonction récursive pour afficher les caractères un par un
    function print() {
      if (i < commands[index].length) {
        var console = document.querySelector(".console");
        console.scrollTop = console.scrollHeight;
        command.innerHTML += commands[index].charAt(i);
        i++;
        setTimeout(print, 15);
      } else {
        cursor.style.display = "none";
        command.innerHTML += "<br/><div>" + responses[index] + "</div><br/>";
        var console = document.querySelector(".console");
        console.scrollTop = console.scrollHeight;

        setTimeout(function () {
          cursor.style.display = "inline-flex";
          type(commands, responses, index + 1);
        }, 500);

      }
    }

    // Lancement de l'affichage
    print();
    isRunning = false;

  }

  // Fonction pour déplacer la souris d'un pixel vers la droite
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
  // Récupération des éléments nécessaires pour le script caché
  let imageContactBg = document.querySelector('.image-contact-bg');
  let secretButton = document.querySelector('.secret');

  if (imageContactBg) {
    // Ajout d'un écouteur d'événement lorsqu'on survole l'image de fond
    imageContactBg.addEventListener('mouseenter', function () {
      secretButton.style.transitionDelay = "2s";
      secretButton.classList.add('active');
      // Attente de 2 secondes avant d'afficher le bouton secret
      setTimeout(function () {
        secretButton.classList.add('hover');
        // script pour décaler la souris de l'utilisateur afin de refresh le cursor pointer
        () => moveCursor();
        // Ajout d'un écouteur d'événement lorsqu'on clique sur le bouton secret
        secretButton.addEventListener('click', function () {
          if (!isRunning) {
            isRunning = true;
            // Attente de 500ms avant de lancer le script caché
            setTimeout(function () {
              var console = document.querySelector(".console");
              console.classList.add('active');
              imageContactBg.style.display = "none";
              type(commands, responses, 0);
            }, 500);
          }
        });
      }, 2000);
    });
    // Ajout d'un écouteur d'événement lorsqu'on quitte la zone de l'image de fond
    imageContactBg.addEventListener('mouseleave', function () {
      secretButton.style.transitionDelay = "0s";
      secretButton.classList.remove('active');
      secretButton.classList.remove('hover');

    });
  }
  if (secretButton) {
    // Ajout d'un écouteur d'événement lorsque l'utilisateur quitte le bouton secret
    secretButton.addEventListener('mouseleave', function () {
      secretButton.style.transitionDelay = "0s";
      secretButton.classList.remove('active');
      secretButton.classList.remove('hover');

    });
  }

  const carrouselContainer = document.querySelector('.carrousel-container');

  if (carrouselContainer) {
    // Initialisation de la position du carrousel
    let position = 0;
    const articles = document.getElementsByClassName('article');
    const carrouselInner = document.getElementsByClassName('carrousel-inner')[0];

    const arrowLeft = document.getElementById('arrow-left');
    const arrowRight = document.getElementById('arrow-right');

    // Fonction pour mettre à jour la visibilité des flèches du carrousel
    function updateArrowsVisibility() {
      const maxPosition = getTotalWidth() - carrouselInner.offsetWidth;

      arrowLeft.style.visibility = position === 0 ? 'hidden' : 'visible';
      arrowRight.style.visibility = position > maxPosition - 100 ? 'hidden' : 'visible';
    }

    // Fonction pour réinitialiser la position du carrousel
    function resetPosition() {
      position = 0;
      carrouselInner.style.transform = `translateX(0)`;
      updateArrowsVisibility();
    }

    // Fonction pour calculer la largeur totale du carrousel
    function getTotalWidth() {
      let totalWidth = 0;
      for (let i = 0; i < articles.length; i++) {
        totalWidth += articles[i].offsetWidth + 20;
      }
      return totalWidth;
    }

    // Fonction pour déplacer le carrousel vers la gauche ou la droite
    function move(direction) {
      const articleWidth = articles[0].offsetWidth + 20;
      const maxPosition = getTotalWidth() - carrouselInner.offsetWidth;

      position += direction * articleWidth;
      position = Math.max(0, Math.min(position, maxPosition));

      carrouselInner.style.transform = `translateX(-${position}px)`;

      updateArrowsVisibility();
    }

    // Ajout des écouteurs d'événements sur les flèches du carrousel
    arrowLeft.addEventListener('click', function () {
      move(-1);
    });

    arrowRight.addEventListener('click', function () {
      move(1);
    });

    // Ajout d'un écouteur d'événement de redimensionnement de la fenêtre pour réinitialiser la position du carrousel
    window.addEventListener('resize', function () {
      resetPosition();
    });

    // Mise à jour de la visibilité des flèches
    updateArrowsVisibility();

  }

  // Trigger animation hover post list
  // Ajout des écouteurs d'événements pour animer les articles de la liste de publications au survol
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
      const url = window.location.href;
      const baseURL = window.location.origin;
      var redirection;
      if (url.indexOf("/posts") !== -1) {
        redirection = baseURL;
      }
      else if (url.indexOf("/post/") !== -1) {

        if (url.indexOf("/post/add-post") !== -1) {
          redirection = baseURL + "/posts";
        }
        else if (url.indexOf("/post/edit-post") !== -1) {
          const editPostPath = "/post/edit-post/";
          const editPostIndex = url.indexOf(editPostPath);
          const postId = url.slice(editPostIndex + editPostPath.length);
          redirection = baseURL + "/post/" + postId;
        }
        else {
          redirection = baseURL + "/posts";
        }
      }
      else if (url.indexOf("/register") !== -1) {
        redirection = baseURL;

      }
      else if (url.indexOf("/login") !== -1) {
        redirection = baseURL;
      }
      newLink.onclick = function () {
        window.location.href = redirection; // Utilise history.go(-1) au lieu de history.back()

        return false;
      }

      // Ajoute le lien comme enfant de l'élément "p"
      navbarParagraph.insertBefore(newLink, navbarParagraph.firstChild);
    }
  }

  // Gestion du script pour afficher/cacher les commentaires d'un article
  const singlePostCommentWrapper = document.querySelector('.single-post-comment-wrapper');

  if (singlePostCommentWrapper) {
    let isExpanded = false;
    const commentHeader = singlePostCommentWrapper.querySelector('h2');

    commentHeader.addEventListener('click', () => {
      if (window.innerWidth > 768) {

        if (isExpanded) {

          const content = document.querySelector('#content');
          content.scrollIntoView({ behavior: 'smooth' });

          singlePostCommentWrapper.style.transition = '0.25s';
          singlePostCommentWrapper.style.maxHeight = '70px';
          isExpanded = false;

          document.querySelector('#ancre-comment').classList.add('disable')
          document.querySelector('#ancre-comment').classList.remove('active')

        } else {
          singlePostCommentWrapper.style.transition = "0s";
          singlePostCommentWrapper.style.maxHeight = '10000px';
          isExpanded = true;

          const addCommentAnchor = document.querySelector('#comment-form');
          document.querySelector('#ancre-comment').classList.add('active')
          document.querySelector('#ancre-comment').classList.remove('disable')

          addCommentAnchor.scrollIntoView({ behavior: 'smooth' });

        }
      }
    });

  }
})










