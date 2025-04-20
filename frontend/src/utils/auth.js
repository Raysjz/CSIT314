export const fetchWithToken = async (url, options = {}) => {
    const token = localStorage.getItem('accessToken');
  
    const res = await fetch(url, {
      ...options,
      headers: {
        ...(options.headers || {}),
        Authorization: `Bearer ${token}`,
      },
    });
  
    if (res.status === 401) {
      await refreshToken();
      return fetchWithToken(url, options); // Retry after refresh
    }
  
    return res;
  };
  
  export const refreshToken = async () => {
    const res = await fetch('http://localhost:3000/refresh', {
      method: 'POST',
      credentials: 'include', // important
    });
  
    const data = await res.json();
    if (res.ok) {
      localStorage.setItem('accessToken', data.accessToken);
    } else {
      console.log('Unable to refresh token');
    }
  };
  