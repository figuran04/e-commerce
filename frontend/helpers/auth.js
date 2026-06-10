/**
 * helpers/auth.js
 * Pustaka autentikasi sisi klien (browser) untuk Zerovaa.
 * Mengelola JWT di localStorage dan menyinkronkan state login dengan session PHP.
 */

const Auth = (() => {
  const TOKEN_KEY  = 'zerovaa_token';
  const USER_KEY   = 'zerovaa_user';
  const BASE_API   = window.location.hostname.endsWith('zerovaa.com')
    ? ''
    : (window.location.pathname.includes('/5/e-commerce/') ? '/5/e-commerce/api' : '/api');

  /** Resolves the proper API endpoint URL depending on the service (auth vs api) and current domain */
  function getEndpointUrl(endpoint) {
    const cleanEndpoint = endpoint.replace(/^\//, '');
    const withSlash = cleanEndpoint.endsWith('/') ? cleanEndpoint : `${cleanEndpoint}/`;
    return `/api/${withSlash}`;
  }

  /** Simpan token dan data user ke localStorage + cookie (agar PHP bisa baca) */
  function save(token, user) {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
    // Simpan juga di cookie agar PHP bisa membaca token langsung
    document.cookie = 'zerovaa_jwt=' + encodeURIComponent(token) + '; path=/; SameSite=Strict; max-age=3600';
  }

  /** Ambil token JWT, atau null jika tidak ada / sudah expired */
  function getToken() {
    const token = localStorage.getItem(TOKEN_KEY);
    if (!token) return null;

    // Decode payload untuk cek expiry (tanpa verifikasi signature)
    try {
      const payload = JSON.parse(atob(token.split('.')[1]));
      if (payload.exp && Date.now() / 1000 > payload.exp) {
        logout(); // Token expired, bersihkan
        return null;
      }
    } catch (e) {
      return null;
    }
    return token;
  }

  /** Ambil data user yang tersimpan, atau null */
  function getUser() {
    try {
      return JSON.parse(localStorage.getItem(USER_KEY));
    } catch {
      return null;
    }
  }

  /** Cek apakah user sedang login */
  function isLoggedIn() {
    return !!getToken();
  }

  /** Hapus token dan data user, redirect ke halaman login */
  async function logout(redirect = true) {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    // Hapus cookie JWT
    document.cookie = 'zerovaa_jwt=; path=/; SameSite=Strict; max-age=0';
    
    // Invalidate session PHP di frontend container
    try {
      const isSubfolder = window.location.pathname.includes('/5/e-commerce/');
      const logoutPath = isSubfolder 
        ? '/5/e-commerce/controllers/api/auth/logout.php' 
        : '/controllers/api/auth/logout.php';
      await fetch(logoutPath, { method: 'POST', credentials: 'same-origin' });
    } catch (e) {
      console.warn('Gagal membersihkan session frontend:', e);
    }

    // Invalidate session PHP via server-side (auth-service)
    try {
      await fetch(getEndpointUrl('auth/logout'), { method: 'POST', credentials: 'same-origin' });
    } catch (e) {}

    if (redirect) {
      const isSubfolder = window.location.pathname.includes('/5/e-commerce/');
      window.location.href = isSubfolder ? '/5/e-commerce/views/login/' : '/views/login/';
    }
  }

  /**
   * Kirim token JWT ke server agar server mem-populate $_SESSION.
   * Dipanggil sekali saat halaman dimuat (di layout.php).
   */
  async function syncSession() {
    const token = getToken();
    if (!token) return;
    try {
      // Dapatkan path dasar yang sesuai (menghandle XAMPP subfolder /5/e-commerce/ jika ada)
      const isSubfolder = window.location.pathname.includes('/5/e-commerce/');
      const syncPath = isSubfolder 
        ? '/5/e-commerce/controllers/auth/sync_session.php' 
        : '/controllers/auth/sync_session.php';

      await fetch(syncPath, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      });
    } catch (e) {
      console.warn('Gagal sinkronisasi session:', e);
    }
  }

  /**
   * Wrapper fetch() yang otomatis menyertakan Authorization header.
   */
  async function apiFetch(endpoint, options = {}) {
    const token = getToken();
    const headers = {
      'Content-Type': 'application/json',
      ...(options.headers || {}),
    };
    if (token) headers['Authorization'] = `Bearer ${token}`;

    const response = await fetch(getEndpointUrl(endpoint), {
      ...options,
      credentials: 'same-origin',
      headers,
    });

    // Token expired di server → paksa logout
    if (response.status === 401) {
      logout();
      return null;
    }
    return response;
  }

  // Ekspor fungsi-fungsi publik
  return { save, getToken, getUser, isLoggedIn, logout, syncSession, apiFetch };
})();
