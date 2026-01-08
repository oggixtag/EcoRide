/**
 * Script de gestion du modal détails du covoiturage
 * Gère l'ouverture, la fermeture et les interactions avec le popup
 */

(function () {
  "use strict";

  // Éléments du DOM
  let modal = null;
  let modalOverlay = null;
  let closeBtn = null;
  let modalContentBody = null;

  /**
   * Initialise le modal lors du chargement du DOM
   */
  function init() {
    modal = document.getElementById("trajet-details-modal");
    if (!modal) {
      // Créer le modal s'il n'existe pas
      createModalTemplate();
      modal = document.getElementById("trajet-details-modal");
    }

    modalOverlay = modal;
    closeBtn = modal.querySelector(".modal-close");
    modalContentBody = modal.querySelector(".modal-content");

    // Événements du modal
    setupEventListeners();

    // Délégation d'événements pour les boutons de détail
    document.addEventListener("click", handleDetailButtonClick);
  }

  /**
   * Crée le template du modal vide
   */
  function createModalTemplate() {
    const template = document.createElement("div");
    template.id = "trajet-details-modal";
    template.className = "modal-overlay";
    template.innerHTML =
      '<div class="modal-content"><button class="modal-close">&times;</button></div>';
    document.body.appendChild(template);
  }

  /**
   * Configure les écouteurs d'événements du modal
   */
  function setupEventListeners() {
    // Fermer via le bouton X
    if (closeBtn) {
      closeBtn.removeEventListener("click", closeModal);
      closeBtn.addEventListener("click", closeModal);
    }

    // Fermer en cliquant sur l'overlay (en dehors du modal)
    if (modalOverlay) {
      modalOverlay.removeEventListener("click", handleOverlayClick);
      modalOverlay.addEventListener("click", handleOverlayClick);
    }

    // Fermer avec la touche Escape
    document.removeEventListener("keydown", handleEscapeKey);
    document.addEventListener("keydown", handleEscapeKey);
  }

  /**
   * Gère le clic sur l'overlay
   */
  function handleOverlayClick(e) {
    if (e.target === modalOverlay) {
      closeModal();
    }
  }

  /**
   * Gère la touche Escape
   */
  function handleEscapeKey(e) {
    if (
      e.key === "Escape" &&
      modalOverlay &&
      modalOverlay.classList.contains("active")
    ) {
      closeModal();
    }
  }

  /**
   * Gère le clic sur les boutons "Détail"
   * @param {Event} e - Événement de clic
   */
  function handleDetailButtonClick(e) {
    const btn = e.target.closest(".btn-detail");
    if (btn) {
      const covoiturageId = btn.dataset.covoiturageId;

      if (covoiturageId) {
        loadAndOpenModal(covoiturageId);
      } else {
        console.warn("covoiturageId not found in button data");
      }
    }
  }

  /**
   * Charge les détails du covoiturage via AJAX et ouvre le modal
   * @param {string|number} covoiturageId - ID du covoiturage
   */
  function loadAndOpenModal(covoiturageId) {
    if (!modal) {
      console.warn("Modal not initialized");
      return;
    }

    // Afficher un message de chargement
    showLoadingState();
    openModal();

    // Charger les détails via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open(
      "GET",
      "index.php?p=trajet-details&id=" + encodeURIComponent(covoiturageId),
      true
    );
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Remplacer le contenu du modal avec la réponse
        modalContentBody.innerHTML = xhr.responseText;

        // Réattacher les écouteurs
        setupEventListeners();
        setupActionButtons();

        console.log("Modal content loaded for covoiturage ID:", covoiturageId);
      } else {
        showErrorState("Erreur lors du chargement des détails.");
        console.error("Error loading modal content:", xhr.status);
      }
    };
    xhr.onerror = function () {
      showErrorState("Erreur réseau lors du chargement des détails.");
      console.error("Network error loading modal content");
    };
    xhr.send();
  }

  /**
   * Ouvre le modal
   */
  function openModal() {
    if (!modal) return;

    modal.classList.add("active");
    document.body.style.overflow = "hidden";
    console.log("Modal opened");
  }

  /**
   * Ferme le modal
   */
  function closeModal() {
    if (!modal) return;

    modal.classList.remove("active");
    document.body.style.overflow = "auto";
    console.log("Modal closed");
  }

  /**
   * Affiche un état de chargement
   */
  function showLoadingState() {
    if (!modalContentBody) return;
    modalContentBody.innerHTML =
      '<button class="modal-close">&times;</button><div style="padding: 40px; text-align: center;"><p>Chargement en cours...</p></div>';
    closeBtn = modalContentBody.querySelector(".modal-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", closeModal);
    }
  }

  /**
   * Affiche un état d'erreur
   * @param {string} message - Message d'erreur
   */
  function showErrorState(message) {
    if (!modalContentBody) return;
    modalContentBody.innerHTML =
      '<button class="modal-close">&times;</button><div style="padding: 40px; text-align: center;"><p style="color: #d32f2f;">' +
      htmlEscape(message) +
      "</p></div>";
    closeBtn = modalContentBody.querySelector(".modal-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", closeModal);
    }
  }

  /**
   * Configure les boutons d'action dans le modal chargé
   */
  function setupActionButtons() {
    const reserveBtn = modalContentBody.querySelector(".btn-reserve");
    if (reserveBtn) {
      reserveBtn.removeEventListener("click", handleReserveClick);
      reserveBtn.addEventListener("click", handleReserveClick);
    }

    const contactBtn = modalContentBody.querySelector(".btn-contact");
    if (contactBtn) {
      contactBtn.removeEventListener("click", handleContactClick);
      contactBtn.addEventListener("click", handleContactClick);
    }
  }

  /**
   * Gère le clic sur le bouton "Réserver"
   */
  function handleReserveClick(e) {
    e.preventDefault();
    alert("Fonctionnalité de réservation à implémenter");
    // TODO: Implémenter la logique de réservation
  }

  /**
   * Gère le clic sur le bouton "Contacter le conducteur"
   */
  function handleContactClick(e) {
    e.preventDefault();
    alert("Fonctionnalité de contact à implémenter");
    // TODO: Implémenter la logique de contact
  }

  /**
   * Échappe les caractères HTML
   * @param {string} text - Texte à échapper
   * @returns {string} Texte échappé
   */
  function htmlEscape(text) {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;",
    };
    return text.replace(/[&<>"']/g, function (m) {
      return map[m];
    });
  }

  /**
   * Expose une fonction publique pour ouvrir le modal
   * Utile si on veut l'appeler depuis d'autres scripts
   */
  window.openTrajetDetailsModal = function (covoiturageId) {
    loadAndOpenModal(covoiturageId);
  };

  /**
   * Expose une fonction publique pour fermer le modal
   */
  window.closeTrajetDetailsModal = function () {
    closeModal();
  };

  // Initialiser quand le DOM est chargé
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
