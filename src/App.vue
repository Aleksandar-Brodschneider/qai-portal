<script setup>
// Za Fazo 2 ne rabimo nič posebnega v scriptu
</script>

<template>
  <div class="app">
    <!-- NAVBAR -->
    <header class="nav">
      <div class="brand">
        QAI Portal
      </div>

      <nav class="links">
        <RouterLink to="/" class="link">Home</RouterLink>
        <RouterLink to="/login" class="link">Login</RouterLink>
        <RouterLink to="/register" class="link">Register</RouterLink>
        <RouterLink to="/admin" class="link">Admin</RouterLink>
        <!-- Dodamo še za raziskave-->
        <RouterLink to="/research" class="link">Research</RouterLink>
        <!-- Dodamo še za userView-->
        <RouterLink to="/users" class="link">Users</RouterLink>
      </nav>
    </header>

    <!-- VSEBINA STRANI -->
    <main class="content">
      <RouterView />
    </main>

    <!-- FOOTER -->
    <footer class="footer">
      Frontend: <code>http://localhost:5173</code> • Backend: <code>http://127.0.0.1:8000</code>
    </footer>
  </div>
</template>

<style scoped>
.app {
  max-width: 1000px;
  margin: 0 auto;
  padding: 16px;
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

.nav {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 14px 16px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: rgba(255, 255, 255, 0.03);
}

.brand {
  font-weight: 700;
  letter-spacing: 0.3px;
}

.links {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.link {
  text-decoration: none;
  padding: 8px 10px;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: rgba(255, 255, 255, 0.04);
  color: inherit;
}

.link:hover {
  background: rgba(255, 255, 255, 0.08);
}

.content {
  margin-top: 16px;
  padding: 16px;
  border-radius: 14px;
  border: 1px solid rgba(255, 255, 255, 0.12);
  background: rgba(255, 255, 255, 0.03);
  min-height: 320px;
}

.footer {
  margin-top: 14px;
  opacity: 0.65;
  font-size: 12px;
}
</style>