<!-- Simple floating chatbot widget for buyers and sellers -->
<div id="chatbot-widget" style="position:fixed;bottom:24px;left:24px;z-index:2000;">
  <button id="chatbot-toggle" class="btn btn-primary rounded-circle shadow" style="width:60px;height:60px;font-size:2rem;background:var(--main-accent,#ff9900);color:var(--main-dark,#232f3e);">
  <i class="bi bi-chat-dots"></i>
  </button>
  <div id="chatbot-box" class="card shadow-lg" style="display:none;width:340px;max-width:90vw;">
    <div class="card-header d-flex align-items-center justify-content-between" style="background:var(--main-accent,#ff9900);color:var(--main-dark,#232f3e);">
      <span><i class="bi bi-robot"></i> grabbasket Assistant</span>
      <button class="btn btn-sm btn-light" onclick="document.getElementById('chatbot-box').style.display='none';"><i class="bi bi-x"></i></button>
    </div>
    <div class="card-body p-2" style="height:260px;overflow-y:auto;">
      <div id="chatbot-messages" style="font-size:0.98rem;"></div>
    </div>
    <div class="card-footer p-2 d-flex flex-column gap-2">
      <form id="chatbot-form" class="d-flex gap-2 mb-1">
        <input type="text" id="chatbot-input" class="form-control" placeholder="Ask me anything..." autocomplete="off">
        <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i></button>
      </form>
      <div class="d-flex gap-2">
        <button id="chatbot-support-btn" class="btn btn-outline-secondary btn-sm flex-grow-1"><i class="bi bi-envelope"></i> Contact Support</button>
        <a href="tel:+918300504230" class="btn btn-outline-success btn-sm flex-grow-1"><i class="bi bi-telephone"></i> Call Support</a>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  const toggle = document.getElementById('chatbot-toggle');
  const box = document.getElementById('chatbot-box');
  const messages = document.getElementById('chatbot-messages');
  // Show welcome message when chat is opened
  let greeted = false;
  toggle.onclick = () => {
    box.style.display = box.style.display==='block' ? 'none' : 'block';
    if(box.style.display==='block' && !greeted) {
      messages.innerHTML += `<div class='mb-2'><b>Bot:</b> Welcome to the grabbasket chatbot! Ask me anything about shopping, selling, or using the website.</div>`;
      messages.scrollTop = messages.scrollHeight;
      greeted = true;
    }
  };
  const form = document.getElementById('chatbot-form');
  const input = document.getElementById('chatbot-input');
  function botReply(msg) {
    let reply = '';
    msg = msg.toLowerCase();
    // Website-wide Q&A logic
    if(msg.includes('order') && msg.includes('cancel')) reply = 'To cancel an order, go to Orders > Track and click Cancel. You can only cancel before it is shipped.';
    else if(msg.includes('order') && msg.includes('track')) reply = 'Track your orders from the Orders page. You will see status and tracking number if shipped.';
    else if(msg.includes('payment')) reply = 'We support Razorpay, UPI, and cards. For payment issues, contact support or check your order status.';
    else if(msg.includes('wishlist')) reply = 'Add products to your wishlist by clicking the heart icon on any product. View your wishlist from the top menu.';
    else if(msg.includes('bulk upload') || (msg.includes('seller') && msg.includes('upload'))) reply = 'Sellers can upload products in bulk from the Admin panel using a CSV template.';
    else if(msg.includes('admin')) reply = 'Admins can manage users, products, orders, and view analytics from the Admin dashboard.';
    else if(msg.includes('image') && msg.includes('upload')) reply = 'Sellers can upload multiple images for each product from their dashboard.';
    else if(msg.includes('category') || msg.includes('subcategory')) reply = 'Products are organized by categories and subcategories. Use the Shop or Category menu to browse.';
    else if(msg.includes('notification')) reply = 'You will receive notifications for order updates, offers, and important messages. Click the bell icon to view.';
    else if(msg.includes('login') || msg.includes('register')) reply = 'You can login or register using the Login link in the top menu. Both buyers and sellers use the same login.';
    else if(msg.includes('gender') && msg.includes('suggestion')) reply = 'We show personalized product suggestions based on your gender and preferences.';
    else if(msg.includes('cart')) reply = 'Add products to your cart and proceed to checkout for payment and delivery.';
    else if(msg.includes('delivery') || msg.includes('shipping')) reply = 'Delivery charges and estimated times are shown on the product and checkout pages.';
    else if(msg.includes('support') || msg.includes('help')) reply = 'You can ask me about orders, payments, uploads, navigation, or contact our support team for more help!';
    else if(msg.includes('logout')) reply = 'Click your profile or the Logout button in the menu to log out.';
    else if(msg.includes('how') && msg.includes('use')) reply = 'Use the navigation bar to shop, manage your cart, wishlist, and orders. Sellers and admins have their own dashboards.';
    else if(msg.includes('about') && msg.includes('website')) reply = 'grabbasket is a modern e-commerce platform for buyers and sellers. Shop, sell, and manage everything in one place!';
    else reply = 'Sorry, I am a simple assistant. Try asking about orders, payments, uploads, navigation, or website features!';
    messages.innerHTML += `<div class='mb-2'><b>Bot:</b> ${reply}</div>`;
    messages.scrollTop = messages.scrollHeight;
  }
  form.onsubmit = function(e){
    e.preventDefault();
    const val = input.value.trim();
    if(!val) return;
    messages.innerHTML += `<div class='mb-2 text-end'><b>You:</b> ${val}</div>`;
    botReply(val);
    input.value = '';
  };
  const supportBtn = document.getElementById('chatbot-support-btn');
  supportBtn.onclick = function() {
    const email = '<?php echo e(Auth::check() ? Auth::user()->email : ""); ?>';
    let question = prompt('Enter your question for support:');
    if(!question) return;
    fetch('/chatbot/support', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        email: email,
        question: question
      })
    }).then(r => r.json()).then(data => {
      messages.innerHTML += `<div class='mb-2'><b>Bot:</b> ${data.success ? 'Your question has been sent to support. You will get a reply by email.' : 'Failed to send. Please try again later.'}</div>`;
      messages.scrollTop = messages.scrollHeight;
    });
  };
})();
</script>
<?php /**PATH E:\e-com_updated_final\e-com_updated\resources\views/components/chatbot-widget.blade.php ENDPATH**/ ?>