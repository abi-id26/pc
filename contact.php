<?php
require_once 'includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="wood-breadcrumbs mb-6">
        <div class="wood-breadcrumb-item">
            <a href="index.php"><i class="fas fa-home mr-1"></i> Home</a>
        </div>
        <div class="wood-breadcrumb-item active">
            Contact Us
        </div>
    </div>

    <h1 class="page-title text-2xl mb-6">Contact Us</h1>

    <div class="flex flex-col lg:flex-row gap-6 mb-12">
        <!-- Contact Info Card (Left) -->
        <div class="w-full lg:w-1/2">
            <div class="wood-card overflow-hidden h-full">
                <div class="wooden-texture-overlay"></div>
                <div class="p-6 relative z-10">
                    <h3 class="text-xl font-bold text-amber-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Our Information
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <div>
                                <strong class="text-amber-900">Address:</strong>
                                <p class="text-amber-800">123 Tech Street, Silicon Valley</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-3">
                                <i class="fas fa-phone"></i>
                            </span>
                            <div>
                                <strong class="text-amber-900">Phone:</strong>
                                <p class="text-amber-800">(123) 456-7890</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-3">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <div>
                                <strong class="text-amber-900">Email:</strong>
                                <p class="text-amber-800">info@pchardwarestore.com</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-amber-100 p-2 rounded-full text-amber-800 mr-3">
                                <i class="fas fa-clock"></i>
                            </span>
                            <div>
                                <strong class="text-amber-900">Hours:</strong>
                                <p class="text-amber-800">Mon-Fri: 9AM-6PM</p>
                            </div>
                        </li>
                    </ul>
                    
                    <div class="wooden-divider my-6"></div>
                    
                    <h3 class="text-xl font-bold text-amber-900 mb-4 flex items-center">
                        <i class="fas fa-share-alt mr-2"></i> Connect With Us
                    </h3>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-amber-100 hover:bg-amber-200 text-amber-800 p-3 rounded-full transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-amber-100 hover:bg-amber-200 text-amber-800 p-3 rounded-full transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="bg-amber-100 hover:bg-amber-200 text-amber-800 p-3 rounded-full transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="bg-amber-100 hover:bg-amber-200 text-amber-800 p-3 rounded-full transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form (Right) -->
        <div class="w-full lg:w-1/2">
            <div class="wood-card overflow-hidden h-full">
                <div class="wooden-texture-overlay"></div>
                <div class="p-6 relative z-10">
                    <h3 class="text-xl font-bold text-amber-900 mb-4 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i> Send Us a Message
                    </h3>
                    <form action="#" method="post" class="space-y-4">
                        <div>
                            <label for="name" class="wood-label block text-amber-900 font-medium mb-2">Your Name</label>
                            <input type="text" id="name" name="name" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" required>
                        </div>
                        <div>
                            <label for="email" class="wood-label block text-amber-900 font-medium mb-2">Your Email</label>
                            <input type="email" id="email" name="email" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" required>
                        </div>
                        <div>
                            <label for="subject" class="wood-label block text-amber-900 font-medium mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" required>
                        </div>
                        <div>
                            <label for="message" class="wood-label block text-amber-900 font-medium mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" class="wood-input w-full px-4 py-2 rounded-md focus:ring focus:ring-amber-200" required></textarea>
                        </div>
                        <div>
                            <button type="submit" class="wooden-cart-button px-6 py-3 bg-amber-800 text-white">
                                <i class="fas fa-paper-plane mr-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="wood-card overflow-hidden mb-8">
        <div class="wooden-texture-overlay"></div>
        <div class="p-6 relative z-10">
            <h3 class="text-xl font-bold text-amber-900 mb-4 flex items-center">
                <i class="fas fa-map-marked-alt mr-2"></i> Find Us
            </h3>
            <div class="rounded-lg overflow-hidden border-4 border-amber-200 shadow-lg">
                <div class="aspect-w-16 aspect-h-9"> <!-- Maintains aspect ratio -->
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3172.3325395304414!2d-121.88380168468822!3d37.3388473798416!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fccb864de43d5%3A0x397ffe721937340e!2sSan%20Jose%2C%20CA!5e0!3m2!1sen!2sus!4v1623251157713!5m2!1sen!2sus" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add wood texture to cards
    $('.wood-card').each(function() {
        $(this).prepend('<div class="wooden-texture-footer absolute inset-0 z-0 opacity-10"></div>');
    });
    
    // Add animation to form inputs
    $('.wood-input').focus(function() {
        $(this).parent().addClass('pulse-animation');
    }).blur(function() {
        $(this).parent().removeClass('pulse-animation');
    });
    
    // Add subtle animation to the form submission button
    $('.wooden-cart-button').hover(
        function() {
            $(this).find('i').animate({ marginRight: '8px' }, 200);
        },
        function() {
            $(this).find('i').animate({ marginRight: '0.5rem' }, 200);
        }
    );
});
</script>

<?php require_once 'includes/footer.php'; ?>