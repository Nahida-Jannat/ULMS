<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Important Contacts - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="shortcut icon" href="../images/logo.png" type="image/x-icon"/>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #2ecc71;
            --info-color: #1abc9c;
            --light-color: #ecf0f1;
            --dark-color: #1a252f;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #333;
            padding: 20px;
        }
        
        .contacts-container {
            max-width: 1400px;
            margin: 2rem auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(250, 250, 252, 0.98) 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border-left: 6px solid var(--secondary-color);
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 1.5rem;
        }
        
        .logo-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .header-section h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.8rem;
            font-size: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
        
        .header-section h1 i {
            color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.1);
            padding: 15px;
            border-radius: 50%;
        }
        
        .header-section .subtitle {
            color: #7f8c8d;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }
        
        .timing-note {
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(243, 156, 18, 0.1));
            border-radius: 15px;
            padding: 1.2rem;
            margin-top: 1.5rem;
            border-left: 4px solid var(--secondary-color);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .contacts-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .contact-card {
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-top: 5px solid var(--secondary-color);
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .contact-card.profile {
            border-top-color: var(--secondary-color);
        }
        
        .contact-card.book {
            border-top-color: var(--info-color);
        }
        
        .contact-card.emergency {
            border-top-color: var(--accent-color);
        }
        
        .contact-card.general {
            border-top-color: var(--success-color);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1.2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            flex-shrink: 0;
        }
        
        .card-icon.profile {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
        }
        
        .card-icon.book {
            background: linear-gradient(135deg, var(--info-color), #16a085);
        }
        
        .card-icon.emergency {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
        }
        
        .card-icon.general {
            background: linear-gradient(135deg, var(--success-color), #27ae60);
        }
        
        .card-header h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
            font-size: 1.2rem;
            line-height: 1.3;
        }
        
        .contact-purpose {
            color: #7f8c8d;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }
        
        .contact-details {
            background: rgba(245, 245, 245, 0.8);
            border-radius: 15px;
            padding: 1rem;
        }
        
        .phone-section {
            margin-bottom: 1rem;
        }
        
        .phone-section h4 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }
        
        .phone-number {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            padding: 6px 10px;
            background: white;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .phone-number:hover {
            background: rgba(52, 152, 219, 0.1);
            transform: translateX(5px);
        }
        
        .phone-number i {
            color: var(--secondary-color);
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .phone-number span {
            color: var(--primary-color);
            font-weight: 500;
            flex: 1;
            font-size: 0.9rem;
            word-break: break-all;
        }
        
        .call-btn {
            background: var(--secondary-color);
            color: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
            font-size: 0.8rem;
        }
        
        .call-btn:hover {
            background: #2980b9;
            transform: scale(1.1);
        }
        
        .email-section h4 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }
        
        .email-address {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: white;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        
        .email-address:hover {
            background: rgba(52, 152, 219, 0.1);
            transform: translateX(5px);
        }
        
        .email-address i {
            color: var(--accent-color);
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .email-address span {
            color: var(--primary-color);
            font-weight: 500;
            flex: 1;
            font-size: 0.85rem;
            word-break: break-all;
        }
        
        .email-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            flex-shrink: 0;
            white-space: nowrap;
        }
        
        .email-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            text-decoration: none;
            color: white;
        }
        
        .footer {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem;
            margin-top: 2rem;
            font-size: 0.9rem;
        }
        
        .back-btn {
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn-back {
            background: linear-gradient(135deg, var(--secondary-color), #2980b9);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
            color: white;
            text-decoration: none;
        }
        
        @media (max-width: 1200px) {
            .contacts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .contact-card {
                padding: 1.8rem;
            }
        }
        
        @media (max-width: 768px) {
            .contacts-container {
                margin: 1rem auto;
            }
            
            .header-section {
                padding: 2rem 1.5rem;
            }
            
            .header-section h1 {
                font-size: 2rem;
            }
            
            .contacts-grid {
                grid-template-columns: 1fr;
            }
            
            .contact-card {
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 576px) {
            .header-section h1 {
                font-size: 1.8rem;
                flex-direction: column;
                gap: 10px;
            }
            
            .card-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .contact-purpose {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
<div class="contacts-container">
    <div class="header-section">
        <div class="logo-container">
            <img src="../images/logo.png" alt="Library Logo" class="logo-img">
        </div>
        
        <h1><i class="fas fa-address-book"></i> Important Contacts</h1>
        
        <p class="subtitle">
            Get in touch with our library team for assistance with profiles, fines, book queries, 
            emergencies, or general inquiries. We're here to help you with all your library needs.
        </p>
        
        <div class="timing-note">
            <i class="fas fa-clock"></i>
            <span>Preferred communication method is email. Phone calls: 10:00 AM to 5:00 PM (Sunday-Thursday)</span>
        </div>
    </div>
    
    <div class="contacts-grid">
        <!-- Profile & Fines Contact Card -->
        <div class="contact-card profile">
            <div class="card-header">
                <div class="card-icon profile">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h3>Profile & Fines Support</h3>
            </div>
            
            <div class="contact-purpose">
                For any profile updates, password resets, fine payments, overdue book issues, 
                or account-related inquiries.
            </div>
            
            <div class="contact-details">
                <div class="phone-section">
                    <h4><i class="fas fa-phone-alt"></i> Phone Numbers</h4>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01601269544 (Primary)</span>
                        <button class="call-btn" onclick="callNumber('01601269544')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01923355579 (Secondary)</span>
                        <button class="call-btn" onclick="callNumber('01923355579')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01711223344 (WhatsApp)</span>
                        <button class="call-btn" onclick="callNumber('01711223344')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                </div>
                
                <div class="email-section">
                    <h4><i class="fas fa-envelope"></i> Email Address</h4>
                    <div class="email-address">
                        <i class="fas fa-at"></i>
                        <span>admin@seu.library.bd</span>
                        <a href="mailto:admin@seu.library.bd" class="email-btn">
                            <i class="fas fa-paper-plane"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Book Queries Contact Card -->
        <div class="contact-card book">
            <div class="card-header">
                <div class="card-icon book">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Book Queries & Reservations</h3>
            </div>
            
            <div class="contact-purpose">
                For book availability, reservations, new book suggestions, genre inquiries, 
                or reading recommendations.
            </div>
            
            <div class="contact-details">
                <div class="phone-section">
                    <h4><i class="fas fa-phone-alt"></i> Phone Numbers</h4>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01680180663 (Primary)</span>
                        <button class="call-btn" onclick="callNumber('01680180663')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01925734040 (Secondary)</span>
                        <button class="call-btn" onclick="callNumber('01925734040')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01876543210 (Catalog Help)</span>
                        <button class="call-btn" onclick="callNumber('01876543210')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                </div>
                
                <div class="email-section">
                    <h4><i class="fas fa-envelope"></i> Email Address</h4>
                    <div class="email-address">
                        <i class="fas fa-at"></i>
                        <span>support@seu.library.bd</span>
                        <a href="mailto:support@seu.library.bd" class="email-btn">
                            <i class="fas fa-paper-plane"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Emergency Contact Card -->
        <div class="contact-card emergency">
            <div class="card-header">
                <div class="card-icon emergency">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Emergency & Technical Support</h3>
            </div>
            
            <div class="contact-purpose">
                For urgent technical issues, website problems, lost books, or emergency 
                situations requiring immediate attention.
            </div>
            
            <div class="contact-details">
                <div class="phone-section">
                    <h4><i class="fas fa-phone-alt"></i> Emergency Numbers</h4>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01755667788 (24/7 Hotline)</span>
                        <button class="call-btn" onclick="callNumber('01755667788')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01998877665 (Technical)</span>
                        <button class="call-btn" onclick="callNumber('01998877665')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01622334455 (Security)</span>
                        <button class="call-btn" onclick="callNumber('01622334455')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                </div>
                
                <div class="email-section">
                    <h4><i class="fas fa-envelope"></i> Email Address</h4>
                    <div class="email-address">
                        <i class="fas fa-at"></i>
                        <span>emergency@seu.library.bd</span>
                        <a href="mailto:emergency@seu.library.bd" class="email-btn">
                            <i class="fas fa-paper-plane"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- General Inquiries Contact Card -->
        <div class="contact-card general">
            <div class="card-header">
                <div class="card-icon general">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h3>General Inquiries</h3>
            </div>
            
            <div class="contact-purpose">
                For general library information, working hours, membership queries, 
                event information, or feedback and suggestions.
            </div>
            
            <div class="contact-details">
                <div class="phone-section">
                    <h4><i class="fas fa-phone-alt"></i> Contact Numbers</h4>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>02-12345678 (Landline)</span>
                        <button class="call-btn" onclick="callNumber('0212345678')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01700112233 (Information)</span>
                        <button class="call-btn" onclick="callNumber('01700112233')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                    <div class="phone-number">
                        <i class="fas fa-phone"></i>
                        <span>01900998877 (Events)</span>
                        <button class="call-btn" onclick="callNumber('01900998877')">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                </div>
                
                <div class="email-section">
                    <h4><i class="fas fa-envelope"></i> Email Address</h4>
                    <div class="email-address">
                        <i class="fas fa-at"></i>
                        <span>info@seu.library.bd</span>
                        <a href="mailto:info@seu.library.bd" class="email-btn">
                            <i class="fas fa-paper-plane"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="back-btn">
        <a href="../index.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Sign In
        </a>
    </div>
</div>

<div class="footer">
    <p>Library Management System &copy; <?php echo date('Y'); ?> | Contact Information</p>
    <small>All contact information is updated regularly. Last updated: <?php echo date('F Y'); ?></small>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add animation to contact cards
    document.addEventListener('DOMContentLoaded', function() {
        const contactCards = document.querySelectorAll('.contact-card');
        contactCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 * index);
        });
        
        // Update current date
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        const dateString = now.toLocaleDateString('en-US', options);
        
        const dateElement = document.createElement('div');
        dateElement.style.marginTop = '10px';
        dateElement.style.color = '#3498db';
        dateElement.style.fontWeight = '500';
        dateElement.style.fontSize = '0.9rem';
        dateElement.innerHTML = '<i class="fas fa-sync-alt"></i> Page loaded on: ' + dateString;
        
        const footer = document.querySelector('.footer');
        if (footer) {
            footer.appendChild(dateElement);
        }
    });
    
    // Simulate call function (for demo purposes)
    function callNumber(number) {
        if (confirm('Call ' + number + '?')) {
            alert('In a real application, this would dial: ' + number + '\n\nFor demo purposes, please use your phone to call this number.');
            // In a real mobile app, you would use: window.location.href = 'tel:' + number;
        }
    }
    
    // Copy email to clipboard
    function copyEmail(email) {
        navigator.clipboard.writeText(email).then(() => {
            alert('Email address copied to clipboard: ' + email);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
</body>
</html>