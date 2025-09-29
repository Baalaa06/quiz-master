// Common JavaScript functions
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function showLoading(element) {
    element.innerHTML = '<div class="loading">Loading...</div>';
}

function hideLoading() {
    const loading = document.querySelector('.loading');
    if (loading) loading.remove();
}

// AJAX helper
function ajaxRequest(url, data = null, method = 'GET') {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    };
    
    if (data && method === 'POST') {
        options.body = new URLSearchParams(data);
    }
    
    return fetch(url, options).then(response => response.json());
}

// Form serialization helper
function serializeForm(form) {
    const formData = new FormData(form);
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    return data;
}

// Timer functionality for quizzes
class QuizTimer {
    constructor(duration, onComplete) {
        this.duration = duration * 60; // convert minutes to seconds
        this.remaining = this.duration;
        this.onComplete = onComplete;
        this.timerElement = null;
        this.interval = null;
    }
    
    start() {
        this.createTimerElement();
        this.interval = setInterval(() => {
            this.remaining--;
            this.updateDisplay();
            
            if (this.remaining <= 0) {
                this.stop();
                this.onComplete();
            }
        }, 1000);
    }
    
    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }
    
    createTimerElement() {
        this.timerElement = document.createElement('div');
        this.timerElement.className = 'quiz-timer';
        document.body.appendChild(this.timerElement);
        this.updateDisplay();
    }
    
    updateDisplay() {
        if (!this.timerElement) return;
        
        const minutes = Math.floor(this.remaining / 60);
        const seconds = this.remaining % 60;
        this.timerElement.textContent = `Time: ${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (this.remaining <= 300) { // 5 minutes warning
            this.timerElement.style.background = '#dc3545';
        }
    }
}