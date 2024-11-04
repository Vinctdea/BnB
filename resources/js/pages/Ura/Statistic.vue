<script>
import { Chart } from "chart.js";
import { store } from "../../store/store";
import dayjs from "dayjs";
export default {
  name: "Statistic",
  data() {
    return {
      allData: [],
      apartmentTitle: "",
      chartInstance: "",
      newAllData: [],
    };
  },
  mounted() {
    this.statisticCall(
      this.$route.params.id,
      this.apartmentTitle,
      "2023-01-01 00:00:00",
      Date.now()
    );
    this.apartmentTitle = this.$route.params.title.split("-").join(" ");
    // this.createTable();
  },
  methods: {
    statisticCall(idApartment, apartmentTitle, startDate, endDate) {
      // Formatta le date nel formato `YYYY-MM-DD HH:mm:ss`
      const formattedStartDate = dayjs(startDate).format("YYYY-MM-DD HH:mm:ss");
      const formattedEndDate = dayjs(endDate).format("YYYY-MM-DD HH:mm:ss");

      axios
        .get("http://127.0.0.1:8000/api/user/view", {
          params: {
            id: idApartment,
            title: apartmentTitle,
            start_date: formattedStartDate,
            end_date: formattedEndDate,
          },
        })
        .then((res) => {
          this.allData = res.data;
          this.createDataTable(
            this.allData,
            formattedStartDate.split("-")[0],
            formattedEndDate.split("-")[0]
          );
        })
        .catch((err) => {
          console.log(err);
        });
    },
    createDataTable(data, startDate, endDate) {
      let result = [];
      let date = new Date(Date.now());
      let start = startDate;

      let end = endDate;
      let monthFilter = date.getMonth() + 1;
      console.log(start, end);
      //   aggiungo le date che non non ci sono che hanno 0 view
      for (let year = start; year <= end; year++) {
        if (year < end) {
          for (let month = 1; month <= 12; month++) {
            // Trova i dati per il mese e l'anno corrente
            const existingEntry = data.find(
              (entry) => entry.year == year && entry.month == month
            );

            if (existingEntry) {
              // Se esiste, lo aggiunge al risultato
              result.push(existingEntry);
            } else {
              // Se non esiste, aggiunge un record con views a 0
              result.push({ year, month, views: 0 });
            }
          }
        } else {
          for (let month = 1; month <= monthFilter; month++) {
            // Trova i dati per il mese e l'anno corrente
            const existingEntry = data.find(
              (entry) => entry.year == year && entry.month == month
            );

            if (existingEntry) {
              // Se esiste, lo aggiunge al risultato
              result.push(existingEntry);
            } else {
              // Se non esiste, aggiunge un record con views a 0
              result.push({ year, month, view_count: 0 });
            }
          }
        }
        this.newAllData = result;
      }

      //   converto i mesi in parole
      let month = result.map((date) => {
        switch (date.month) {
          case 1:
            return "Gennaio";
          case 2:
            return "Febbraio";
          case 3:
            return "Marzo";
          case 4:
            return "Aprile";
          case 5:
            return "Maggio";
          case 6:
            return "Giugno";
          case 7:
            return "Luglio";
          case 8:
            return "Agosto";
          case 9:
            return "Settembre";
          case 10:
            return "Ottobre";
          case 11:
            return "Novembre";
          case 12:
            return "Dicembre";
        }
      });

      // prendo tutte le view di tutti i mesi
      let view = result.map((date) => {
        return date.view_count;
      });

      this.createTable({
        month,
        view,
      });
    },
    createTable(data) {
      if (this.chartInstance) {
        this.chartInstance.destroy(); // Distrugge il grafico precedente
      }
      this.chartInstance = new Chart(
        document.getElementById("myChart").getContext("2d"),
        {
          type: "bar",
          data: {
            labels: data.month,
            datasets: [
              {
                label: "Visualizzazioni",
                data: data.view,
                backgroundColor: "#FF5757",
                borderColor: "#FF5757",
                borderWidth: 1,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        }
      );
    },
    filtredApartment(event) {
      let year = event.target.value;
      let result;
      if (year !== "all") {
        result = this.newAllData.filter((date) => {
          return date.year == year;
        });
      } else {
        result = this.newAllData;
      }
      //   converto i mesi in parole
      let month = result.map((date) => {
        switch (date.month) {
          case 1:
            return "Gennaio";
          case 2:
            return "Febbraio";
          case 3:
            return "Marzo";
          case 4:
            return "Aprile";
          case 5:
            return "Maggio";
          case 6:
            return "Giugno";
          case 7:
            return "Luglio";
          case 8:
            return "Agosto";
          case 9:
            return "Settembre";
          case 10:
            return "Ottobre";
          case 11:
            return "Novembre";
          case 12:
            return "Dicembre";
        }
      });

      // prendo tutte le view di tutti i mesi
      let view = result.map((date) => {
        return date.view_count;
      });

      this.createTable({
        month,
        view,
      });
    },
  },
  computed: {
    selectedOption() {
      return [
        ...new Set(
          this.allData.map((data) => {
            return data.year;
          })
        ),
      ];
    },
    title() {
      return this.apartmentTitle;
    },
  },
};
</script>
<template>
  <div
    class="ms_container d-flex flex-column align-items-center justify-content-center"
  >
    <div class="d-flex justify-content-center align-items-center gap-4">
      <h3 class="mt-4">Visualizzazioni per {{ title }}</h3>
      <div class="box">
        <select @change="filtredApartment">
          <option value="all">2023-2024:</option>
          <option
            v-for="(year, index) in selectedOption"
            :key="index"
            :value="year"
          >
            {{ year }}
          </option>
        </select>
      </div>
    </div>
    <canvas id="myChart"></canvas>
  </div>
</template>
<style lang='scss' scoped>
.ms_container {
  width: 100%;
  height: 80vh;
  margin: 20px 0;
  padding: 20px;
}

.box {
}

.box select {
  background-color: red;
  color: white;
  padding: 12px;
  width: 250px;
  border: none;
  font-size: 20px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
  -webkit-appearance: button;
  appearance: button;
  outline: none;
}

.box::before {
  content: "\f13a";
  font-family: FontAwesome;
  position: absolute;
  top: 0;
  right: 0;
  width: 20%;
  height: 100%;
  text-align: center;
  font-size: 28px;
  line-height: 45px;
  color: rgba(255, 255, 255, 0.5);
  background-color: rgba(255, 255, 255, 0.1);
  pointer-events: none;
}

.box:hover::before {
  color: rgba(255, 255, 255, 0.6);
  background-color: rgba(255, 255, 255, 0.2);
}

.box select option {
  padding: 30px;
}
// @use 'path' as *;
</style>
