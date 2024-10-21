<template>
    <button @click="deleteProduct">Dar de baja</button>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: ['productId'],
    methods: {
      deleteProduct() {
        if (confirm('¿Estás seguro de dar de baja este producto?')) {
          axios.delete(`/products/${this.productId}`)
            .then(response => {
              alert('Producto dado de baja correctamente');
              this.$emit('productDeleted', this.productId);
            })
            .catch(error => {
              console.error(error);
            });
        }
      },
    },
  };
  </script>