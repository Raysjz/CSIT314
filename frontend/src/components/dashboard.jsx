import React, { useEffect, useState } from 'react';
import { fetchWithToken } from '../utils/auth';

function Dashboard() {
  const [data, setData] = useState(null);

  useEffect(() => {
    const loadProtectedData = async () => {
      const res = await fetchWithToken('http://localhost:3000/protected');
      const result = await res.json();
      setData(result);
    };
    loadProtectedData();
  }, []);

  return (
    <div>
      <h2>Dashboard</h2>
      {data ? <pre>{JSON.stringify(data, null, 2)}</pre> : 'Loading...'}
    </div>
  );
}

export default Dashboard;
