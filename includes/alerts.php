<?php
// includes/alerts.php
if (isset($_SESSION['flash_message'])): 
    $msg = $_SESSION['flash_message'];
    // Unset immediately so it doesn't persist
    unset($_SESSION['flash_message']);
?>
    <div class="alert alert-<?php echo $msg['type']; ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
        <?php if ($msg['type'] == 'success'): ?>
            <i class="fas fa-check-circle me-2"></i>
        <?php elseif ($msg['type'] == 'danger'): ?>
            <i class="fas fa-exclamation-circle me-2"></i>
        <?php elseif ($msg['type'] == 'warning'): ?>
            <i class="fas fa-exclamation-triangle me-2"></i>
        <?php else: ?>
            <i class="fas fa-info-circle me-2"></i>
        <?php endif; ?>
        
        <?php echo $msg['text']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
