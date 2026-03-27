import { createIcons, icons } from 'lucide';
import Chart from 'chart.js/auto';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.lucide = { createIcons, icons };
window.lucide.createIcons({ icons: window.lucide.icons });

window.Chart = Chart;

window.Alpine = Alpine;
Alpine.start();

window.Swal = Swal;

document.addEventListener("DOMContentLoaded", function() {
    const fadeEls = document.querySelectorAll('.fade-up, .fade-down, .fade-left, .fade-right');
    
    if (fadeEls.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                }
            });
        }, {
            threshold: 0.15 
        });
        
        fadeEls.forEach(el => observer.observe(el));
    }
});

window.confirmSoftDelete = function(url, entityName = 'item') {
    Swal.fire({
        title: 'Move to Trash?',
        text: `Are you sure you want to remove this ${entityName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, move to trash!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
};

window.confirmAction = function(actionType, url, csrfToken, entityName = 'item') {
    let title, text, icon, confirmButtonText, confirmButtonColor, method;

    if (actionType === 'restore') {
        title = 'Restore?';
        text = "Are you sure you want to restore this?";
        icon = 'question';
        confirmButtonText = 'Yes, restore!';
        confirmButtonColor = '#10B981';
        method = 'PATCH';
    } else if (actionType === 'delete') {
        title = 'Move to Trash?';
        text = `Are you sure you want to remove this ${entityName}?`;
        icon = 'warning';
        confirmButtonText = 'Yes, move to trash!';
        confirmButtonColor = '#EF4444';
        method = 'DELETE';
    } else if (actionType === 'forceDelete') {
        title = 'Permanent Delete?';
        text = "This cannot be undone! Are you sure?";
        icon = 'warning';
        confirmButtonText = 'Yes, delete permanently!';
        confirmButtonColor = '#EF4444';
        method = 'DELETE';
    } else if (actionType === 'approve') {
        title = 'Approve/Activate Review?';
        text = "This review will be visible to the public.";
        icon = 'question';
        confirmButtonText = 'Yes, approve it!';
        confirmButtonColor = '#10B981';
        method = 'PATCH';
    } else if (actionType === 'deactivate') {
        title = 'Deactivate Review?';
        text = "This will hide the review from the public views.";
        icon = 'warning';
        confirmButtonText = 'Yes, hide it!';
        confirmButtonColor = '#F59E0B';
        method = 'PATCH';
    }

    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: confirmButtonColor,
        cancelButtonColor: '#6B7280',
        confirmButtonText: confirmButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); 
                } else {
                    Swal.fire('Error!', data.message || 'Action failed.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            });
        }
    });
};

document.addEventListener("DOMContentLoaded", function() {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    const successFlash = document.getElementById('flash-success');
    if (successFlash) {
        Toast.fire({
            icon: 'success',
            title: successFlash.getAttribute('data-message')
        });
    }

    const errorFlash = document.getElementById('flash-error');
    if (errorFlash) {
        Toast.fire({
            icon: 'error',
            title: errorFlash.getAttribute('data-message')
        });
    }
});