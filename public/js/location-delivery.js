/**
 * Location-Based Delivery System for 10-Minute Express Delivery
 * Fetches products within 2km range using user's current location
 */

class LocationDelivery {
    constructor() {
        this.userLat = null;
        this.userLng = null;
        this.radiusKm = 2;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    /**
     * Get user's current location using Geolocation API
     */
    getUserLocation() {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation is not supported by this browser'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.userLat = position.coords.latitude;
                    this.userLng = position.coords.longitude;
                    resolve({ lat: this.userLat, lng: this.userLng });
                },
                (error) => {
                    reject(error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    }

    /**
     * Store location in session for the server
     */
    async storeLocationInSession() {
        try {
            const response = await fetch('/store-location', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    latitude: this.userLat,
                    longitude: this.userLng,
                    address: await this.getAddressFromCoordinates(this.userLat, this.userLng),
                }),
            });

            if (!response.ok) throw new Error('Failed to store location');
            return await response.json();
        } catch (error) {
            console.error('Error storing location:', error);
            throw error;
        }
    }

    /**
     * Get products within specified radius using location
     */
    async getLocationBasedProducts(categoryId = null, radiusKm = 2, limit = 12) {
        try {
            const response = await fetch('/api/location-based-products', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({
                    latitude: this.userLat,
                    longitude: this.userLng,
                    radius_km: radiusKm,
                    category_id: categoryId,
                    limit: limit,
                }),
            });

            if (!response.ok) throw new Error('Failed to fetch location-based products');
            return await response.json();
        } catch (error) {
            console.error('Error fetching location-based products:', error);
            throw error;
        }
    }

    /**
     * Initialize location-based delivery on page load
     */
    async initialize() {
        try {
            console.log('Initializing location-based delivery...');
            
            // Try to get user's location
            const location = await this.getUserLocation();
            console.log('User location:', location);

            // Store location in session
            await this.storeLocationInSession();
            console.log('Location stored in session');

            // Fetch and display nearby products
            const products = await this.getLocationBasedProducts();
            console.log('Fetched products:', products);

            return products;
        } catch (error) {
            console.warn('Location-based delivery initialization failed:', error);
            console.log('Falling back to standard product display');
            return null;
        }
    }

    /**
     * Get address from coordinates (Reverse Geocoding)
     * Using Nominatim (OpenStreetMap) - no API key required
     */
    async getAddressFromCoordinates(lat, lng) {
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
            );

            if (!response.ok) throw new Error('Geocoding failed');
            const data = await response.json();
            return data.address?.city || data.address?.town || data.address?.village || 'Unknown Location';
        } catch (error) {
            console.warn('Geocoding error:', error);
            return null;
        }
    }

    /**
     * Display products on the page
     */
    displayProducts(productsData, containerId = 'products-grid') {
        const container = document.getElementById(containerId);
        if (!container) {
            console.warn(`Container #${containerId} not found`);
            return;
        }

        if (!productsData?.data || productsData.data.length === 0) {
            container.innerHTML = '<p class="text-center col-12">No products found within ' + this.radiusKm + 'km</p>';
            return;
        }

        container.innerHTML = productsData.data.map(product => `
            <div class="product-card animate-fade-in">
                <img src="${product.image_url || '/images/no-image.png'}" 
                     alt="${product.name}" 
                     class="product-image"
                     onerror="this.src='/images/no-image.png'">
                
                <div style="padding: 12px;">
                    <div style="font-size: 0.85rem; font-weight: 500; color: #0C831F; margin-bottom: 4px;">
                        <i class="bi bi-geo-alt-fill"></i> ${product.distance_km}km away
                    </div>
                    
                    <div class="product-title">${product.name}</div>
                    
                    <div style="font-size: 0.75rem; color: #666; margin: 4px 0;">
                        ${product.seller}
                    </div>
                    
                    <div class="product-price">
                        <span class="current-price">₹${parseFloat(product.price).toFixed(2)}</span>
                    </div>

                    <button class="add-to-cart-btn" onclick="addToCart(${product.id})">
                        <i class="bi bi-cart-plus"></i> Add
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Get distance between two coordinates in km
     */
    calculateDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth's radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Distance in km
    }

    /**
     * Request location permission and set up auto-refresh
     */
    async setupAutoRefresh(intervalMinutes = 5) {
        setInterval(async () => {
            try {
                const location = await this.getUserLocation();
                console.log('Auto-refreshing location:', location);

                const products = await this.getLocationBasedProducts();
                if (products?.data) {
                    this.displayProducts(products);
                }
            } catch (error) {
                console.warn('Auto-refresh failed:', error);
            }
        }, intervalMinutes * 60 * 1000);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
    if (document.body.dataset.deliveryMode === '10-minute' || window.location.pathname.includes('10-minute-delivery')) {
        const locationDelivery = new LocationDelivery();
        
        // Try to initialize location-based delivery
        try {
            await locationDelivery.initialize();
            
            // Set up auto-refresh every 5 minutes
            locationDelivery.setupAutoRefresh(5);
        } catch (error) {
            console.log('Location-based delivery not available');
        }
    }
});

// Expose to global scope for manual triggering
window.LocationDelivery = LocationDelivery;
