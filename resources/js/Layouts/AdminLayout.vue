<template>
    <div class="admin-layout">
      <!-- Sidebar -->
      <nav class="sidebar bg-dark text-white">
        <div class="sidebar-header text-center py-3">
          <h3>Admin Panel</h3>
        </div>
        <ul class="nav flex-column">
          <li class="nav-item">
            <router-link class="nav-link text-white" to="/">Trang chủ</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link text-white" to="/categories">Danh mục</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link text-white" to="/products">Sản phẩm</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link text-white" to="/sizes">Sizes</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link text-white" to="/colors">Màu sắc</router-link>
          </li>
        </ul>
      </nav>
  
      <!-- Main Content -->
      <main class="main-content">
        <header class="header bg-light d-flex justify-content-between align-items-center p-3 shadow-sm">
          <h4 class="mb-0">Dashboard</h4>
          <div class="user-info">
            <span>Xin chào, Admin</span>
          </div>
        </header>
        <div class="content p-4">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  const name = 'AdminLayout';
  </script>
  
  <style lang="scss" scoped>
  
  .admin-layout {
    display: flex;
    height: 100vh;
  }
  
  .sidebar {
    width: 250px;
    min-height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
  
    .sidebar-header {
      font-size: 1.5rem;
      font-weight: bold;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
  
    .nav-link {
      padding: 10px 20px;
      font-size: 1rem;
      &:hover {
        background-color: rgba(255, 255, 255, 0.1);
      }
    }
  }
  
  .main-content {
    margin-left: 250px;
    width: calc(100% - 250px);
    display: flex;
    flex-direction: column;
  
    .header {
      height: 60px;
      border-bottom: 1px solid #ddd;
    }
  
    .content {
      flex: 1;
      overflow-y: auto;
      background-color: #f8f9fa;
    }
  }
  </style>
  