<template>

    <div class="product">

     <!-- set up image carousell for scrolling -->
      <div class="product-images">
          <img :src="Image" class="main-image">

          <div class="carousell-nav">
            <button class="carousell-button prev" @click="prevImage"><div class="arrow"></div></button>
            <button class="carousell-button next" @click="nextImage"><div class="arrow"></div></button>
          </div>
          <!-- set up container of images for display -->
        <div class="image-container">
          <div v-for="(image, index) in product.images" :key="index" 
               class="container-wrapper"
               :class="{ 'active': Image === image }"
               @click="Image = image">
            <img :src="image" class="container">
          </div>
        </div>

      </div>

      <div class="company-name">SNEAKER COMPANY</div>

      <!-- product information -->
      <div class="product-info">
        <h1 class="product-slug">{{ product.name }}</h1>
        <p class="product-description">{{ product.description }}</p>

        <div class="price-detail">
            <div class="price-section">
                <span class="discounted-price">${{ product.price.discounted.toFixed(2) }}</span>
                <span class="discount">{{ product.discount.amount }}%</span>
            </div>
            <span class="original-price">${{ product.price.full.toFixed(2) }}</span>
        </div>

        <!-- add to cart function -->
        <div class="add-to-cart-container">
          <div class="quantity">
            <button @click="decreaseQ">-</button>
            <span>{{ quantity }}</span>
            <button @click="increaseQ">+</button>
          </div>

          <button class="add-to-cart">
            <span>Add to cart</span>
          </button>
          
        </div>
      </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
    name: 'Product',
    setup() {
        const route = useRoute()
        const router = useRouter()

        const product = ref({
            name: '',
            description: '',
            price: { full: 0, discounted: 0 },
            discount: { amount: 0 },
            images: []
        })
        const Image = ref('')
        const quantity = ref(0)
        const error = ref(null)
        const Images = ref(0)

        const fetchProduct = async (slug) => {
            try {
                const response = await axios.get(`/client/products/${slug}`)
                if (response.data && response.data.data) {
                    product.value = response.data.data
                    if (product.value.images && product.value.images.length > 0) {
                        Image.value = product.value.images[0]
                    }
                } else {
                    error.value = 'Invalid data structure received from API'
                }
            } catch (err) {
                router.push('/not-found')
                console.error('Error fetching product:', err)
            }
        }

        const prevImage = () => {
        Images.value = (Images.value - 1 + product.value.images.length) % product.value.images.length
            Image.value = product.value.images[Images.value]
        }

        const nextImage = () => {
            Images.value = (Images.value + 1) % product.value.images.length
            Image.value = product.value.images[Images.value]
        }
  
        const increaseQ = () => {
            quantity.value++
        }
    
        const decreaseQ = () => {
            if (quantity.value > 0) quantity.value--
        }
  
        onMounted(() => {
            const slug = route.params.slug
            fetchProduct(slug)
        })
  
        return {
            product,
            Image,
            quantity,
            error,
            increaseQ,
            decreaseQ,
            prevImage,
            nextImage
        }
    }
}
</script>

<style scoped>
.product {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1440px;
  margin: 0 auto;
  padding: 10%;
  font-family: 'Kumbh Sans', sans-serif;
}

.product-images, .product-info {
  width: 45%;
}

.carousell-nav {
  display: none;
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  justify-content: space-between;
  padding: 0 20px;
}

.carousell-button {
  background-color: white;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.arrow {
  width: 10px;
  height: 10px;
  border-style: solid;
  border-color: #1D2026;
  border-width: 2px 2px 0 0;
  display: inline-block;
}

.prev .arrow {
  transform: rotate(-135deg);
}

.next .arrow {
  transform: rotate(45deg);
}

.main-image {
  width: 100%;
  border-radius: 20px;
  margin-bottom: 20px;
}

.company-name {
  display: none;
  color: hsl(26, 100%, 55%);
  font-weight: 700;
  font-size: 0.8rem;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-top: 20px;
  margin-bottom: 10px;
}

.image-container {
  display: flex;
  justify-content: space-between;
}

.container-wrapper {
  width: 22%;
  border-radius: 10px;
  cursor: pointer;
  overflow: hidden;
  aspect-ratio: 1 / 1;
}

.container {
  width: 100%;
  height: 100%;
  object-fit: cover; 
  transition: opacity 0.3s;
}

.container-wrapper:not(.active) .container:hover {
  opacity: 0.7;
}

.container-wrapper.active {
  border: 2px solid hsl(26, 100%, 55%);
}

.container-wrapper.active .container {
  opacity: 0.5;
}

.product-slug {
  font-size: 50px;
  font-weight: 700;
  margin-bottom: 20px;
}

.product-description {
  color: hsl(219, 9%, 45%);
  line-height: 1.6;
  margin-bottom: 30px;
}

.price-detail {
  margin-bottom: 30px;
}

.price-section {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.discounted-price {
  font-size: 32px;
  font-weight: 700;
  color: #000000;
  margin-right: 15px;
}

.discount {
  background-color: hsl(25, 100%, 94%);
  color: hsl(26, 100%, 55%);
  padding: 4px 8px;
  border-radius: 6px;
  font-weight: 700;
}

.original-price {
  text-decoration: line-through;
  color: hsl(220, 14%, 75%);
  font-size: 16px;
}

.add-to-cart-container {
  display: flex;
  align-items: center;
  gap: 15px;
}

.quantity {
  display: flex;
  align-items: center;
  background-color: hsl(223, 64%, 98%);
  border-radius: 10px;
  overflow: hidden;
}

.quantity button, .quantity span {
  padding: 15px 20px;
  border: none;
  background: none;
  font-size: 16px;
  font-weight: 700;
}

.quantity button {
  color: hsl(26, 100%, 55%);
  cursor: pointer;
}

.add-to-cart {
  background-color: hsl(26, 100%, 55%);
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
  box-shadow: 0 20px 30px -10px hsl(26, 100%, 55%, 0.3);
}


@media (max-width: 768px) {
  .product {
    flex-direction: column;
    padding: 20px;
  }

  .product-info {
    width: 100%;
  }

  .product-slug {
    font-size: 36px;
    text-align: left;
  }

  .company-name {
    display: block;
  }

  .product-description {
    text-align: left;
    margin-bottom: 20px;
  }
  
  .main-image {
    width: 100%;
    border-radius: 0;
  }

  .product-images {
    position: relative;
    width: 100vw;
    margin-left: -20px;
    margin-right: -20px;
  }

  .carousell-nav {
    display: flex;
  }

  .image-container {
    display: none;
  }

  .price-detail {
    text-align: center;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
  }

  .price-section {
    justify-content: center;
  }

  .original-price {
    margin-top: 10px;
  }

  .quantity, .add-to-cart {
    width: 100%;
  }

  .quantity {
    justify-content: space-between;
    font-size: 16px;
  }

  .quantity button, .quantity span {
    padding: 10px 20px;
  }

  .quantity button {
    font-size: 16px;
    padding: 10px;
  }

  .add-to-cart-container {
    flex-direction: column;
    width: 100%;
    margin-top: 20px;
  }

  .add-to-cart {
    margin-top: 10px;
  }

}

</style>