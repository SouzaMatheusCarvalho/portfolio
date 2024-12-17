function toggleMenu() {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                navbar.classList.toggle('active');
            }
        }
        
        function setActiveLink() {
            const linkId = 'projeto-link'; 
            const linkElement = document.getElementById(linkId);
        
            if (linkElement) {
                linkElement.classList.add('active');
            }
        }
        
        var slideIndex = 1; 
        
        function openModal() {
            document.getElementById('myModal').style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }
        
        function plusSlides(n) {
            showSlides(slideIndex += n);
        }
        
        function currentSlide(n) {
            showSlides(slideIndex = n);
        }
        
        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName('mySlides');
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = 'none';
            }
            slides[slideIndex - 1].style.display = 'block';
        }
        
        function setupZoom() {
            document.querySelectorAll('.modal img, .modal video').forEach(function(media) {
                var zoomed = false;
                var startX, startY, initialX, initialY;
        
                media.addEventListener('click', function() {
                    if (zoomed) {
                        media.classList.remove('zoomed');
                        media.style.transformOrigin = ''; 
                        media.style.transform = ''; 
                        zoomed = false;
                    } else {
                        media.classList.add('zoomed');
                        zoomed = true;
                        media.style.transformOrigin = 'center center';
                    }
                });
        
                media.addEventListener('touchstart', function(e) {
                    if (zoomed) {
                        startX = e.touches[0].clientX;
                        startY = e.touches[0].clientY;
                        var transform = media.style.transform.replace(/[^0-9.,-]/g, '').split(',');
                        initialX = parseFloat(transform[0]) || 0;
                        initialY = parseFloat(transform[1]) || 0;
                    }
                });
        
                media.addEventListener('touchmove', function(e) {
                    if (!zoomed) return;
        
                    let dx = e.touches[0].clientX - startX;
                    let dy = e.touches[0].clientY - startY;
                    media.style.transform = 'scale(2) translate(' + (initialX + dx) + 'px, ' + (initialY + dy) + 'px)';
                });
        
                media.addEventListener('mousemove', function(e) {
                    if (!zoomed) return;
        
                    var rect = media.getBoundingClientRect();
                    var x = (e.clientX - rect.left) / rect.width * 100;
                    var y = (e.clientY - rect.top) / rect.height * 100;
        
                    media.style.transformOrigin = x + '% ' + y + '%';
                });
        
                media.addEventListener('mouseleave', function() {
                    if (zoomed) {
                        media.classList.remove('zoomed');
                        media.style.transform = '';
                        zoomed = false;
                    }
                });
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('JavaScript do projeto carregado');
        
            toggleMenu(); 
            setActiveLink(); 
            setupZoom(); 
        
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' || event.keyCode === 27) {
                    closeModal();
                }
            });
        });