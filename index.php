<!DOCTYPE html>
</nav>

<div class="container mt-4">

    <div class="bg-primary text-white p-5 rounded mb-4">
        <h1>Promo IoT Hingga 20%</h1>
        <p>Belanja perangkat IoT terbaik.</p>
    </div>

    <div class="row" id="product-list"></div>

</div>

<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-database-compat.js"></script>

<script>

// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDzpGIXovPyS7fBrHPi0x1hxw9COqIsCMc",
    authDomain: "uts-ecommerce.firebaseapp.com",
    databaseURL: "https://uts-ecommerce-default-rtdb.asia-southeast1.firebasedatabase.app",
    projectId: "uts-ecommerce",
    storageBucket: "uts-ecommerce.firebasestorage.app",
    messagingSenderId: "1000827941217",
    appId: "1:1000827941217:web:7390ae2d3a4edaa2cb9520",
    measurementId: "G-DLKH86LNGW"
};
firebase.initializeApp(firebaseConfig);

const db = firebase.database();

const productList = document.getElementById('product-list');


 db.ref('products').once('value', function(snapshot) {

    snapshot.forEach(function(childSnapshot) {

        let product = childSnapshot.val();

        productList.innerHTML += `
            <div class="col-md-4">
                <div class="product-card">
                    <img src="assets/images/${product.image}">
                    <h4>${product.name}</h4>
                    <p>${product.description}</p>
                    <h5>Rp ${product.price}</h5>

                    <button class="btn btn-success w-100">
                        Add To Cart
                    </button>
                </div>
            </div>
        `;

    });

});

</script>

</body>
</html>