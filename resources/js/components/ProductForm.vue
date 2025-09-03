<template>
  <div class="container mt-4">
    <div class="card shadow">
      <div class="card-body">
        <h2 class="card-title mb-4">
          {{ isEdit ? "Edit Product" : "Create Product" }}
        </h2>

        <!-- Step navigation -->
        <div class="mb-4">
          <button
            v-for="n in 3"
            :key="n"
            class="btn me-2"
            :class="step === n ? 'btn-primary' : 'btn-outline-secondary'"
            @click="step = n"
          >
            Step {{ n }}
          </button>
        </div>

        <!-- Step 1: Basic details -->
        <div v-if="step === 1">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input v-model="form.name" type="text" class="form-control" />
            <small v-if="errors.name" class="text-danger">{{ errors.name }}</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Category</label>
            <div class="input-group">
              <select v-model="form.category" class="form-select">
                <option value="">-- Select --</option>
                <option v-for="cat in categories" :key="cat" :value="cat">
                  {{ cat }}
                </option>
              </select>
              <input
                type="text"
                class="form-control"
                placeholder="New category"
                v-model="newCategory"
                @keyup.enter="addCategory"
              />
              <button class="btn btn-outline-success" @click="addCategory">
                Add
              </button>
            </div>
            <small v-if="errors.category" class="text-danger">{{ errors.category }}</small>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea
              v-model="form.description"
              class="form-control"
              rows="4"
            ></textarea>
            <small v-if="errors.description" class="text-danger">{{ errors.description }}</small>
          </div>
        </div>

        <!-- Step 2: Images -->
        <div v-if="step === 2">
          <div class="mb-3">
            <label class="form-label">Images</label>
            <input
              type="file"
              multiple
              accept="image/*"
              @change="handleFileUpload"
              class="form-control"
            />
            <small v-if="errors.images" class="text-danger">{{ errors.images }}</small>
          </div>

          <div v-if="existingImages.length" class="mb-3">
            <label class="form-label">Current Images</label>
            <div class="d-flex flex-wrap gap-2">
              <div
                v-for="(img, i) in existingImages"
                :key="`existing-${i}`"
                class="position-relative"
                style="width: 100px; height: 100px;"
              >
                <img
                  :src="`/storage/${img}`"
                  alt="Existing Image"
                  class="img-thumbnail"
                  style="width: 100%; height: 100%; object-fit: cover;"
                />
                <button
                  type="button"
                  class="btn btn-sm btn-danger position-absolute top-0 end-0"
                  style="padding: 2px 6px; font-size: 12px;"
                  @click="removeExistingImage(i)"
                >
                  &times;
                </button>
              </div>
            </div>
          </div>

          <!-- Image Preview -->
          <div v-if="form.images.length" class="mb-3">
            <label class="form-label">New Images</label>
            <div class="d-flex flex-wrap gap-2">
              <div
                v-for="(file, i) in form.images"
                :key="`new-${i}`"
                class="position-relative"
                style="width: 100px; height: 100px;"
              >
                <img
                  :src="URL.createObjectURL(file)"
                  alt="New Image"
                  class="img-thumbnail"
                  style="width: 100%; height: 100%; object-fit: cover;"
                />
                <button
                  type="button"
                  class="btn btn-sm btn-danger position-absolute top-0 end-0"
                  style="padding: 2px 6px; font-size: 12px;"
                  @click="removeNewImage(i)"
                >
                  &times;
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Step 3: Date -->
        <div v-if="step === 3">
          <div class="mb-3">
            <label class="form-label">Date</label>
            <input v-model="form.date" type="date" class="form-control" />
            <small v-if="errors.date" class="text-danger">{{ errors.date }}</small>
          </div>
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-between mt-4">
          <button v-if="step > 1" class="btn btn-secondary" @click="prevStep">
            Back
          </button>
          <button v-if="step < 3" class="btn btn-primary" @click="nextStep">
            Next
          </button>
          <button v-if="step === 3" class="btn btn-success" @click="submitForm">
            {{ isEdit ? "Update" : "Submit" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const step = ref(1);
const isEdit = ref(false);
const categories = ref([]);
const newCategory = ref("");
const existingImages = ref([]);
const errors = ref({});
const form = ref({
  name: "",
  category: "",
  description: "",
  images: [],
  date: new Date().toISOString().substr(0, 10),
});

const urlParams = new URLSearchParams(window.location.search);
const productId = urlParams.get("productId");

// Fetch categories
const fetchCategories = async () => {
  try {
    const res = await axios.get("/api/products/categories");
    categories.value = res.data;
  } catch (e) {
    console.error("Error fetching categories", e);
  }
};

// Load product data for editing
const loadProduct = async (id) => {
  try {
    const res = await axios.get(`/api/products/${id}`);
    form.value.name = res.data.name;
    form.value.category = res.data.category;
    form.value.description = res.data.description;
    form.value.date = res.data.dateTime
      ? res.data.dateTime.substr(0, 10)
      : new Date().toISOString().substr(0, 10);
    existingImages.value = res.data.images || [];
    form.value.images = [];
    isEdit.value = true;
  } catch (e) {
    console.error("Error loading product", e);
  }
};

// Add new category
const addCategory = () => {
  if (newCategory.value && !categories.value.includes(newCategory.value)) {
    categories.value.push(newCategory.value);
    form.value.category = newCategory.value;
    newCategory.value = "";
  }
};

// Handle new images
const handleFileUpload = (e) => {
  form.value.images = Array.from(e.target.files);
};

// Remove images
const removeExistingImage = (index) => {
  existingImages.value.splice(index, 1);
};
const removeNewImage = (index) => {
  form.value.images.splice(index, 1);
};

// Step validation
const validateStep = () => {
  errors.value = {};

  if (step.value === 1) {
    if (!form.value.name.trim()) errors.value.name = "Name is required";
    if (!form.value.category.trim()) errors.value.category = "Category is required";
    if (!form.value.description.trim()) errors.value.description = "Description is required";
  }

  if (step.value === 2) {
    if (existingImages.value.length === 0 && form.value.images.length === 0)
      errors.value.images = "At least one image is required";
  }

  if (step.value === 3) {
    if (!form.value.date) errors.value.date = "Date is required";
  }

  return Object.keys(errors.value).length === 0;
};

// Step navigation
const nextStep = () => {
  if (validateStep()) step.value++;
};
const prevStep = () => step.value--;

// Submit form
const submitForm = async () => {
  if (!validateStep()) return;

  try {
    const payload = new FormData();
    payload.append("name", form.value.name);
    payload.append("category", form.value.category);
    payload.append("description", form.value.description);
    payload.append("date", form.value.date);
    payload.append("existing_images", JSON.stringify(existingImages.value));

    form.value.images.forEach((file, i) => {
      payload.append(`images[${i}]`, file);
    });

    if (isEdit.value) {
      await axios.post(`/api/products/${productId}?_method=PUT`, payload, {
        headers: { "Content-Type": "multipart/form-data" },
      });
    } else {
      await axios.post("/api/products", payload, {
        headers: { "Content-Type": "multipart/form-data" },
      });
    }

    window.location.href = "/products";
  } catch (e) {
    console.error("Error saving product", e);
  }
};

onMounted(() => {
  fetchCategories();
  if (productId) loadProduct(productId);
});
</script>
