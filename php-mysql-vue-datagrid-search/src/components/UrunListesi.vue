<template>
  <div>
    <h2>Ürün Listesi</h2>

    <!-- Arama Kısmı -->
    <input v-model="searchQuery" placeholder="Ürün Ara" />

    <!-- Kategori Filtreleme -->
    <select v-model="selectedCategory">
      <option value="">Tüm Kategoriler</option>
      <option v-for="category in categories" :key="category" :value="category">
        {{ category }}
      </option>
    </select>

    <!-- Marka Filtreleme -->
    <select v-model="selectedBrand">
      <option value="">Tüm Markalar</option>
      <option v-for="brand in brands" :key="brand" :value="brand">
        {{ brand }}
      </option>
    </select>

    <!-- Sıralama -->
    <div>
      <button @click="sortTable('ad')">Ürün Adı Sıralama</button>
      <button @click="sortTable('fiyat')">Fiyat Sıralama</button>
    </div>

    <!-- Tablo -->
    <table border="1">
      <thead>
        <tr>
          <!-- <th>ID</th> -->
          <th>Ürün Adı</th>
          <th>Fiyat</th>
          <th>Kategori</th>
          <th>Marka</th>
          <th>Etiketler</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="urun in filteredProducts" :key="urun.id">
          <!-- <td>{{ urun.id }}</td> -->
          <td>{{ urun.ad }}</td>
          <td>{{ urun.fiyat }}₺</td>
          <td>{{ urun.kategori }}</td>
          <td>{{ urun.marka }}</td>
          <td>{{ urun.etiketler }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      urunler: [], // API'den gelen ürünler listesi
      searchQuery: "", // Arama sorgusu
      selectedCategory: "", // Seçilen kategori
      selectedBrand: "", // Seçilen marka
      categories: [], // Kategoriler
      brands: [], // Markalar
      sortKey: "id", // Varsayılan sıralama anahtarı
      sortDirection: "asc", // Varsayılan sıralama yönü
    };
  },
  computed: {
  filteredProducts() {
    return this.urunler
      .filter(urun => 
        // Kategori ve Marka filtresi
        (this.selectedCategory === "" || urun.kategori === this.selectedCategory) &&
        (this.selectedBrand === "" || urun.marka === this.selectedBrand) &&
        // Arama filtresi: Hem ürün adı hem de etiketlerde arama
        (this.searchQuery === "" || 
          urun.ad.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
          urun.etiketler.toLowerCase().includes(this.searchQuery.toLowerCase())
        )
      )
      .sort((a, b) => {
        let modifier = this.sortDirection === "asc" ? 1 : -1;
        
        // Fiyat sıralaması: Sayısal veriler için
        if (this.sortKey === "fiyat") {
          a = parseFloat(a[this.sortKey]);
          b = parseFloat(b[this.sortKey]);
        } else {
          // Diğer sıralamalarda string sıralaması yapıyoruz
          a = a[this.sortKey] !== undefined ? a[this.sortKey].toString().toLowerCase() : "";
          b = b[this.sortKey] !== undefined ? b[this.sortKey].toString().toLowerCase() : "";
        }
        
        // Karşılaştırma yapıyoruz
        if (a < b) return -1 * modifier;
        if (a > b) return 1 * modifier;
        return 0;
      });
  }
},
  mounted() {
    this.fetchCategories();
    this.fetchBrands();
    this.fetchProducts();
  },
  methods: {
    async fetchProducts() {
      try {
        const response = await axios.get("http://localhost/eticaret-arama/backend/urunleri_getir.php");
        this.urunler = response.data;
      } catch (error) {
        console.error("Ürünler yüklenirken hata:", error);
      }
    },
    async fetchCategories() {
      try {
        const response = await axios.get("http://localhost/eticaret-arama/backend/kategorileri_getir.php");
        this.categories = response.data;
      } catch (error) {
        console.error("Kategoriler alınırken hata:", error);
      }
    },
    async fetchBrands() {
      try {
        const response = await axios.get("http://localhost/eticaret-arama/backend/markalari_getir.php");
        this.brands = response.data;
      } catch (error) {
        console.error("Markalar alınırken hata:", error);
      }
    },
    sortTable(column) {
      if (this.sortKey === column) {
        this.sortDirection = this.sortDirection === "asc" ? "desc" : "asc";
      } else {
        this.sortKey = column;
        this.sortDirection = "asc";
      }
    }
  }
};
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
  cursor: pointer;
}

th:hover {
  background-color: #ddd;
}

tr:nth-child(even) {
  background-color: #f9f9f9;
}

input, select {
  margin: 10px;
  padding: 8px;
  width: 200px;
}
</style>
