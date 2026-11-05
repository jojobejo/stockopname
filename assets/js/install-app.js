(function () {
  'use strict';
  var appUrl = 'https://10.10.10.26/stockopname/';
  var pendingPrompt = null;

  function hideInstallButtons() {
    document.querySelectorAll('[data-install-app]').forEach(function (button) {
      button.style.display = 'none';
    });
  }

  if (window.matchMedia('(display-mode: standalone)').matches || navigator.standalone) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', hideInstallButtons);
    } else {
      hideInstallButtons();
    }
  }

  if (location.origin === new URL(appUrl).origin && 'serviceWorker' in navigator) {
    navigator.serviceWorker.register('/stockopname/sw.js', { scope: '/stockopname/' }).catch(function () {});
  }

  window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    pendingPrompt = event;
  });

  window.addEventListener('appinstalled', function () {
    pendingPrompt = null;
    hideInstallButtons();
  });

  document.addEventListener('click', function (event) {
    var button = event.target.closest('[data-install-app]');
    if (!button) return;
    if (location.origin !== new URL(appUrl).origin) {
      location.href = appUrl;
      return;
    }
    if (pendingPrompt) {
      pendingPrompt.prompt();
      pendingPrompt = null;
      return;
    }
    var ios = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    alert(ios
      ? 'Buka menu Bagikan pada browser, lalu pilih Tambahkan ke Layar Utama.'
      : 'Buka menu browser (tiga titik), lalu pilih Instal aplikasi atau Tambahkan ke layar utama.');
  });
}());
