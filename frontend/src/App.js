import React from 'react';
import './App.css'
import {
  Box,
  Button,
  MenuItem,
  Select,
  TextField,
  Typography,
  AppBar,
  Toolbar,
  Tabs,
  Tab,
  Container
} from '@mui/material';

export default function CreateAccountForm() {
  const [role, setRole] = React.useState('');

  const handleRoleChange = (event) => {
    setRole(event.target.value);
  };

  return (
    <Box>
      <AppBar position="static" color="default" sx={{ mb: 4 }}>
        <Toolbar variant="dense">
          <Tabs value={2} textColor="inherit">
            <Tab label="Dashboard" />
            <Tab label="View Account/Profile" />
            <Tab label="Create Account" disabled />
            <Tab label="Create Profile" />
          </Tabs>
          <Box sx={{ flexGrow: 1 }} />
          <Button variant="outlined" size="small">Login</Button>
        </Toolbar>
      </AppBar>

      <Container maxWidth="sm">
        <Box display="flex" flexDirection="column" gap={2}>
          <TextField label="Full Name" variant="outlined" fullWidth />
          <TextField label="Username" variant="outlined" fullWidth />
          <TextField label="Email" variant="outlined" fullWidth />
          <TextField label="Password" type="password" variant="outlined" fullWidth />
          <Select
            value={role}
            onChange={handleRoleChange}
            displayEmpty
            fullWidth
            variant="outlined"
          >
            <MenuItem value="">
              <em>Select Role</em>
            </MenuItem>
            <MenuItem value="admin">Admin</MenuItem>
            <MenuItem value="user">User</MenuItem>
          </Select>

          <Box display="flex" justifyContent="space-between" mt={2}>
            <Button variant="contained" color="secondary">Back</Button>
            <Button variant="contained" color="primary">Create Account</Button>
          </Box>
        </Box>
      </Container>
    </Box>
  );
}
