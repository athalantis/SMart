<style>
    /* Base Styles */
    .card {
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        overflow: hidden;
        background: #ffffff;
    }
    
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12);
    }
    
    /* Card Image Container */
    .card-img-container {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
    }
    
    .card-img-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.02) 0%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }
    
    .object-fit-cover {
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }
    
    .card:hover .object-fit-cover {
        transform: scale(1.05);
    }
    
    /* Card Body */
    .card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #ffffff 0%, #f9f9f9 100%);
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .card-price {
        font-size: 1.2rem;
        color: #27ae60;
    }
    
    /* Badge Styles */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }
    
    /* Rating Styles */
    .text-warning {
        color: #f39c12 !important;
        letter-spacing: 1px;
    }
    
    /* Button Styles */
    .btn-primary {
        background-color: #3498db;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
    }
    
    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-primary.disabled {
        background-color: #95a5a6;
        cursor: not-allowed;
    }
    
    /* Modal Styles */
    .modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .modal-header {
        border-bottom: 1px solid #e0e0e0;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #e0e0e0;
        padding: 1rem 1.5rem;
    }
    
    .btn-outline-secondary {
        border-radius: 8px;
        padding: 0.5rem 1rem;
        border: 1px solid #bdc3c7;
        color: #7f8c8d;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #95a5a6;
        color: #34495e;
    }
    
    /* Form Styles */
    .form-control {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        border: 1px solid #dfe6e9;
    }
    
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }
    
    .form-label {
        font-weight: 500;
        color: #34495e;
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        color: #7f8c8d;
        font-size: 0.85rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 767.98px) {
        .card {
            margin-bottom: 1.5rem;
        }
        
        .modal-dialog {
            margin: 1rem;
        }
    }
    
    /* Animation for Empty State */
    .alert-info {
        border-radius: 10px;
        background-color: #e8f4fc;
        border-color: #b8e0fa;
        color: #3498db;
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>