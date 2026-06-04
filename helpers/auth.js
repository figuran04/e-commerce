/**
 * helpers/auth.js
 * Pustaka autentikasi sisi klien (browser) untuk Zerovaa.
 * Mengelola JWT di localStorage dan menyinkronkan state login dengan session PHP.
 */

const Auth = (() => {
  const TOKEN_KEY  = 'zerovaa_token';
  const USER_KEY   = 'zerovaa_user';
  const BASE_API   = '/5/e-commerce/api';

  /** Simpan token dan data user ke localStorage */
  function save(token, user) {
    localStorage.setItem(TOKEN_KEY, token);
    localStorage.setItem(USER_KEY, JSON.stringify(user));
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
  function logout(redirect = true) {
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    // Invalidate session PHP via server-side
    fetch(`${BASE_API}/auth/logout`, { method: 'POST' }).catch(() => {});
    if (redirect) {
      window.location.href = '/5/e-commerce/views/login';
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
      await fetch(`${BASE_API}/auth/sync_session`, {
        method: 'POST',
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

    const response = await fetch(`${BASE_API}/${endpoint}`, {
      ...options,
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
