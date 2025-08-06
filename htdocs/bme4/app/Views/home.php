<?= $this->include('layouts/template/header') ?>

<!-- Prominent Company Header with Logout -->
<div class="company-header" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid #dee2e6; padding: 2rem 0; margin-bottom: 2rem;">
    <div class="container-fluid">
        <div class="row justify-content-between align-items-center">
            <!-- Left: Logo and Title -->
            <div class="col-auto d-flex align-items-center">
                <img src="<?= base_url('assets/img/nwfth-logo.png') ?>" 
                     alt="NWFTH Company Logo" 
                     style="height: 80px; width: auto; object-fit: contain;">
                <h1 class="company-title mb-0 ml-4" style="color: #000000; font-weight: 700; font-size: clamp(1.8rem, 4vw, 3rem); text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    NWFTH - Run Creation System
                </h1>
            </div>
            
            <!-- Right: User Info and Logout -->
            <div class="col-auto d-flex align-items-center user-logout-section" style="gap: 1rem;">
                <!-- User Info -->
                <div class="user-info d-none d-md-flex align-items-center" style="color: #6c757d; font-weight: 600; font-size: 0.95rem;">
                    <i class="fas fa-user mr-2" style="color: #8d4004; font-size: 1.1rem;"></i>
                    <strong style="color: #8d4004; font-size: 0.95rem;"><?= session()->get('username') ?: session()->get('full_name') ?: 'User' ?></strong>
                </div>
                
                <!-- Logout Button -->
                <a href="<?= base_url('auth/logout') ?>" 
                   class="btn btn-logout-home d-flex align-items-center" 
                   onclick="return confirm('Are you sure you want to logout?')"
                   style="background: #8d4004; 
                          color: white; 
                          border: none; 
                          padding: 0.75rem 1.5rem; 
                          border-radius: 0.5rem; 
                          font-weight: 600; 
                          text-decoration: none; 
                          transition: all 0.2s ease;
                          gap: 0.5rem;
                          box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                          font-size: 0.95rem;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-sm-inline">Logout</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- NWFTH Run Creation System -->
<div class="minimal-interface" style="min-height: 60vh; display: flex; align-items: center; justify-content: center;">

    <!-- Main Menu Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 3rem; max-width: 900px; width: 100%;">
        
        <!-- Create Bulk Run Card -->
        <div class="minimal-card navigation-card" style="cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; text-align: center; padding: 3rem 2rem;" 
             onclick="window.location.href='<?= base_url('CreateRunBulk') ?>'">
            <div style="margin-bottom: 1.5rem;">
                <div style="width: 80px; height: 80px; background: var(--color-primary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <i class="fas fa-boxes" style="color: white; font-size: 2.5rem;"></i>
                </div>
            </div>
            <h3 style="font-size: var(--font-size-2xl); font-weight: 600; color: var(--color-gray-900); margin: 0;">
                Create Bulk Run
            </h3>
        </div>

        <!-- Create Partial Run Card -->
        <div class="minimal-card navigation-card" style="cursor: pointer; transition: transform 0.2s ease, box-shadow 0.2s ease; text-align: center; padding: 3rem 2rem;" 
             onclick="window.location.href='<?= base_url('CreateRunPartial') ?>'">
            <div style="margin-bottom: 1.5rem;">
                <div style="width: 80px; height: 80px; background: var(--color-primary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                    <i class="fas fa-clipboard-check" style="color: white; font-size: 2.5rem;"></i>
                </div>
            </div>
            <h3 style="font-size: var(--font-size-2xl); font-weight: 600; color: var(--color-gray-900); margin: 0;">
                Create Partial Run
            </h3>
        </div>

    </div>

</div>

<style>
/* Company Header Responsive Styles */
.company-header {
    transition: all 0.3s ease;
}

/* Logout Button Hover Effects */
.btn-logout-home:hover {
    background: #a0470a !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
    color: white !important;
    text-decoration: none !important;
}

.btn-logout-home:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) !important;
}

/* User Info Styling */
.user-info {
    margin-right: 0.5rem;
}

.user-logout-section {
    min-height: 48px;
    align-items: center;
}

@media (max-width: 768px) {
    .company-header .row {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .company-header .col-auto:first-child {
        margin-bottom: 1rem;
    }
    
    .company-header .col-auto:first-child h1 {
        margin-left: 0 !important;
        margin-top: 1rem;
    }
    
    .company-header img {
        height: 60px !important;
    }
    
    .user-logout-section {
        justify-content: center;
        gap: 0.5rem !important;
    }
    
    .user-info {
        display: flex !important; /* Show on mobile too */
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .user-info i {
        font-size: 1rem !important;
    }
    
    .user-info strong {
        font-size: 0.9rem !important;
    }
}

@media (max-width: 576px) {
    .company-header {
        padding: 1.5rem 1rem !important;
    }
    
    .company-header img {
        height: 50px !important;
    }
}

/* Enhanced card hover effects */
.navigation-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

/* Card icon animation */
.navigation-card:hover .fas {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

/* Smooth transitions for interactive elements */
.navigation-card * {
    transition: all 0.2s ease;
}

/* Click feedback */
.navigation-card:active {
    transform: translateY(-2px) scale(0.98);
}

/* Logo hover effect */
.company-header img:hover {
    transform: scale(1.05);
    transition: transform 0.2s ease;
}
</style>

<script>
// Simple navigation enhancement
$(document).ready(function() {
    // Add click feedback for cards
    $('.navigation-card').on('mousedown', function() {
        $(this).css('transform', 'translateY(-2px) scale(0.98)');
    }).on('mouseup mouseleave', function() {
        $(this).css('transform', '');
    });
    
    console.log('NWFTH Run Creation System loaded successfully');
});
</script>

<?= $this->include('layouts/template/footer') ?>