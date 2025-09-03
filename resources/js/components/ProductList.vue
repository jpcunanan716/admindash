<template>
  <div class="container mt-4">
    <h2 class="mb-3">Products</h2>

    <!-- Search and Filter -->
    <div class="d-flex mb-3 gap-2">
      <input
        type="text"
        v-model="search"
        placeholder="Search products..."
        class="form-control"
        @input="fetchProducts"
      />

      <select v-model="category" class="form-select" @change="fetchProducts">
        <option value="">All </option>
        <option v-for="cat in categories" :key="cat" :value="cat">
          {{ cat }}
        </option>
      </select>

      <button class="btn btn-primary" @click="goToCreate">Create</button>
    </div>

    <!-- Products Table -->
    <table class="table table-bordered">
      <thead>
        <tr>
          <th @click="sort('id')" style="cursor:pointer">ID</th>
          <th @click="sort('name')" style="cursor:pointer">Name</th>
          <th @click="sort('category')" style="cursor:pointer">Category</th>
          <th>Description</th>
          <th>Images</th>
          <th>Date and Time</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.id }}</td>
          <td>{{ product.name }}</td>
          <td>{{ product.category }}</td>
          <td>{{ product.description }}</td>
          <td>
            <div v-if="product.images && product.images.length" class="d-flex flex-wrap gap-1">
              <img
                v-for="(img, i) in product.images"
                :key="i"
                :src="`/storage/${img}`"
                alt="Product Image"
                class="img-thumbnail"
                style="width: 120px; height: 120px; object-fit: cover;"
              />
            </div>
          <span v-else>No images</span>
          </td>
          <td>{{ product.dateTime }}</td>
          <td>
            <button
              class="btn btn-sm btn-warning me-2"
              @click="goToEdit(product.id)"
            >
              Edit
            </button>
            <button
              class="btn btn-sm btn-danger"
              @click="deleteProduct(product.id)"
            >
              Delete
            </button>
          </td>
        </tr>
        <tr v-if="products.length === 0">
          <td colspan="5" class="text-center">No products found</td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <nav v-if="meta.total > meta.per_page" class="mt-3">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: !meta.prev_page_url }">
          <button
            class="page-link"
            @click="changePage(meta.current_page - 1)"
            :disabled="!meta.prev_page_url"
          >
            Previous
          </button>
        </li>

        <li
          v-for="page in meta.last_page"
          :key="page"
          class="page-item"
          :class="{ active: page === meta.current_page }"
        >
          <button class="page-link" @click="changePage(page)">
            {{ page }}
          </button>
        </li>

        <li class="page-item" :class="{ disabled: !meta.next_page_url }">
          <button
            class="page-link"
            @click="changePage(meta.current_page + 1)"
            :disabled="!meta.next_page_url"
          >
            Next
          </button>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      products: [],
      categories: [],
      search: "",
      category: "",
      sortBy: "name",
      sortDirection: "asc",
      meta: {
        current_page: 1,
        last_page: 1,
        total: 0,
        per_page: 10,
        prev_page_url: null,
        next_page_url: null,
      },
    };
  },
  methods: {
    async fetchProducts(page = 1) {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/products", {
          params: {
            page,
            per_page: this.meta.per_page,
            search: this.search,
            category: this.category,
            sort_field: this.sortBy,
            sort_direction: this.sortDirection,
          },
        });
        this.products = res.data.data;
        this.meta = res.data.meta;
      } catch (err) {
        console.error("Error fetching products:", err);
      }
    },
    async fetchCategories() {
      try {
        const res = await axios.get(
          "http://127.0.0.1:8000/api/products/categories"
        );
        this.categories = res.data;
      } catch (err) {
        console.error("Error fetching categories:", err);
      }
    },
    sort(column) {
      if (this.sortBy === column) {
        this.sortDirection =
          this.sortDirection === "asc" ? "desc" : "asc";
      } else {
        this.sortBy = column;
        this.sortDirection = "asc";
      }
      this.fetchProducts();
    },
    changePage(page) {
      if (page > 0 && page <= this.meta.last_page) {
        this.fetchProducts(page);
      }
    },
    goToCreate() {
       window.location.href = "/products/create";
    },
    goToEdit(id) {
      window.location.href = `/products/create?productId=${id}`;
    },
    async deleteProduct(id) {
      if (!confirm("Are you sure?")) return;
      try {
        await axios.delete(`http://127.0.0.1:8000/api/products/${id}`);
        this.fetchProducts();
      } catch (err) {
        console.error("Error deleting product:", err);
      }
    },
  },
  mounted() {
    this.fetchProducts();
    this.fetchCategories();
  },
};
</script>
