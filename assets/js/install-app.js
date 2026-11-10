(function () {
  'use strict';

  var manifest = document.querySelector('link[rel="manifest"]');
  var appUrl = new URL('.', manifest ? manifest.href : new URL('/stockopname/', location.origin)).toString();
  var pendingPrompt = null;
  var installedHere = false;
  var userAgent = navigator.userAgent || '';
  var isIpadDesktopMode = navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
  var isMobile = Boolean((navigator.userAgentData && navigator.userAgentData.mobile) ||
    /Android|iPhone|iPad|iPod|Mobile|Windows Phone/i.test(userAgent) || isIpadDesktopMode);

  function isInstalledDisplay() {
    return window.matchMedia('(display-mode: fullscreen)').matches ||
      window.matchMedia('(display-mode: standalone)').matches || navigator.standalone === true;
  }

  function updateInstallControls() {
    // Tombol atau menu pasang aplikasi disembunyikan sementara untuk semua pengguna
    document.querySelectorAll('[data-install-app]').forEach(function (button) {
      button.hidden = true;
      button.style.display = 'none';
    });
    document.querySelectorAll('[data-install-label]').forEach(function (label) {
      label.textContent = isMobile ? 'Download pintasan aplikasi' : 'Pasang aplikasi di komputer';
    });
    document.querySelectorAll('[data-device-label]').forEach(function (label) {
      label.textContent = '';
      label.style.display = 'none';
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateInstallControls);
  } else {
    updateInstallControls();
  }

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register(new URL('sw.js', appUrl).toString(), { scope: appUrl }).catch(function () {});
  }

  window.addEventListener('beforeinstallprompt', function (event) {
    event.preventDefault();
    pendingPrompt = event;
  });

  window.addEventListener('appinstalled', function () {
    pendingPrompt = null;
    installedHere = true;
    updateInstallControls();
  });

  document.addEventListener('click', function (event) {
    var button = event.target.closest('[data-install-app]');
    if (!button) return;

    if (pendingPrompt) {
      pendingPrompt.prompt();
      pendingPrompt = null;
      return;
    }

    if (!window.isSecureContext) {
      alert('Buka ' + appUrl + ' melalui HTTPS dengan sertifikat yang dipercaya perangkat, lalu pasang pintasan dari menu browser.');
      return;
    }

    if (isMobile) {
      alert(/iPhone|iPad|iPod/i.test(userAgent) || isIpadDesktopMode
        ? 'Buka menu Bagikan pada browser, lalu pilih Tambahkan ke Layar Utama. Buka ikon yang dibuat untuk tampilan layar penuh.'
        : 'Buka menu browser (tiga titik), lalu pilih Instal aplikasi atau Tambahkan ke layar utama. Buka ikon yang dibuat untuk tampilan layar penuh.');
      return;
    }

    alert('Buka menu browser komputer, lalu pilih Instal aplikasi atau Buat pintasan jika tersedia.');
  });
}());
