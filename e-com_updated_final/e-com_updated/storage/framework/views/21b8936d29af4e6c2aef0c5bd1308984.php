<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .notification-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #dee2e6;
            transition: all 0.2s ease;
        }
        .notification-item:hover {
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        }
        .notification-item.unread {
            border-left-color: #007bff;
            background-color: #f8f9ff;
        }
        .notification-item.order_received {
            border-left-color: #28a745;
        }
        .notification-item.order_placed {
            border-left-color: #17a2b8;
        }
        .notification-item.order_status_update {
            border-left-color: #ffc107;
        }
    </style>
</head>
<body>
    <?php if (isset($component)) { $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.back-button','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('back-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $attributes = $__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__attributesOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687)): ?>
<?php $component = $__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687; ?>
<?php unset($__componentOriginal5c84f04e4e4c3f6b2afa5416a6776687); ?>
<?php endif; ?>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#"><i class="bi bi-bell-fill"></i> Notifications</a>
            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo e(route('buyer.dashboard')); ?>" class="btn btn-light btn-sm"><i class="bi bi-house"></i> Dashboard</a>
                <button class="btn btn-outline-light btn-sm" id="mark-all-read">
                    <i class="bi bi-check2-all"></i> Mark All as Read
                </button>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h3 class="mb-4"><i class="bi bi-bell text-primary"></i> Your Notifications</h3>

                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <?php if($notifications->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash" style="font-size: 5rem; color: #ccc;"></i>
                        <h4 class="mt-3 text-muted">No notifications</h4>
                        <p class="text-muted">You're all caught up!</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="notification-item <?php echo e($notification->type); ?> <?php echo e($notification->isRead() ? '' : 'unread'); ?>" 
                             data-notification-id="<?php echo e($notification->id); ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2">
                                            <?php if($notification->type === 'order_received'): ?>
                                                <i class="bi bi-bag-check-fill text-success"></i>
                                            <?php elseif($notification->type === 'order_placed'): ?>
                                                <i class="bi bi-cart-check-fill text-info"></i>
                                            <?php elseif($notification->type === 'order_status_update'): ?>
                                                <i class="bi bi-truck text-warning"></i>
                                            <?php else: ?>
                                                <i class="bi bi-info-circle text-primary"></i>
                                            <?php endif; ?>
                                        </div>
                                        <h6 class="mb-0 fw-semibold"><?php echo e($notification->title); ?></h6>
                                        <?php if(!$notification->isRead()): ?>
                                            <span class="badge bg-primary ms-2">New</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="mb-2 text-muted"><?php echo e($notification->message); ?></p>
                                    
                                    <?php if($notification->data): ?>
                                        <div class="small text-secondary">
                                            <?php if(isset($notification->data['order_id'])): ?>
                                                <span class="badge bg-light text-dark">Order #<?php echo e($notification->data['order_id']); ?></span>
                                            <?php endif; ?>
                                            <?php if(isset($notification->data['product_name'])): ?>
                                                <span class="badge bg-light text-dark"><?php echo e($notification->data['product_name']); ?></span>
                                            <?php endif; ?>
                                            <?php if(isset($notification->data['amount'])): ?>
                                                <span class="badge bg-light text-dark">₹<?php echo e(number_format($notification->data['amount'], 2)); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="text-end">
                                    <small class="text-muted"><?php echo e($notification->created_at->diffForHumans()); ?></small>
                                    <?php if(!$notification->isRead()): ?>
                                        <br>
                                        <button class="btn btn-sm btn-outline-primary mark-as-read mt-1" 
                                                data-notification-id="<?php echo e($notification->id); ?>">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($notifications->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Mark individual notification as read
        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = this.getAttribute('data-notification-id');
                markAsRead(notificationId);
            });
        });
        
        // Mark all notifications as read
        document.getElementById('mark-all-read').addEventListener('click', function() {
            fetch('<?php echo e(route("notifications.markAllAsRead")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    notificationElement.classList.remove('unread');
                    const markButton = notificationElement.querySelector('.mark-as-read');
                    if (markButton) {
                        markButton.remove();
                    }
                    const newBadge = notificationElement.querySelector('.badge.bg-primary');
                    if (newBadge) {
                        newBadge.remove();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html><?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/notifications/index.blade.php ENDPATH**/ ?>