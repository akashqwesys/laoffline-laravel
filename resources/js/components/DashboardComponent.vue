<template>
  <div class="container-fluid">
    <div class="nk-content-inner">
      <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
          <div class="nk-block-between">
            <div class="nk-block-head-content">
              <h3 class="nk-block-title page-title">Dashboard</h3>
            </div>
          </div>
        </div>
        <div class="nk-block">
          <div class="row g-gs">
            <div class="col-md-8">
              <div class="card card-bordered h-100">
                <div class="card-inner">
                  <div class="card-title-group align-start pb-3 g-2">
                    <div class="card-title card-title-sm">
                      <h6 class="title">Daily Commission Report</h6>
                      <p>
                        Last 30 days commission report.
                      </p>
                    </div>
                    <div class="card-tools">
                      <em class="card-hint icon ni ni-help" data-bs-toggle="tooltip" data-bs-placement="left"
                        title="Commission receive of this month"></em>
                    </div>
                  </div>
                  <div class="">
                    <div class="" style="height: 300px;">
                      <canvas class="analytics-au-chart" id="chartDailyCommissionInvoice"
                        ref="chartDailyCommissionInvoice"></canvas>
                    </div>
                    <div class="chart-label-group">
                      <div class="chart-label">01 Dec, 2024</div>
                      <div class="chart-label">31 Dec, 2024</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card card-bordered card-full">
                <div class="card-inner">
                  <div class="card-title-group align-start mb-0">
                    <div class="card-title">
                      <h6 class="subtitle">Total Parties</h6>
                    </div>
                    <div class="card-tools">
                      <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left"
                        title="Total Parties"></em>
                    </div>
                  </div>
                  <div class="card-amount">
                    <span class="amount">
                      5,569
                    </span>
                  </div>
                  <div class="invest-data">
                    <div class="invest-data-amount g-2">
                      <div class="invest-data-history">
                        <div class="title">Customers</div>
                        <div class="amount">
                          2,940
                        </div>
                      </div>
                      <div class="invest-data-history">
                        <div class="title">Supplier</div>
                        <div class="amount">
                          1,259
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mt-3">
                    <div class="progress-list gy-3">
                      <div class="progress-wrap">
                        <div class="progress-text">
                          <div class="progress-label">Retailer</div>
                          <div class="progress-amount">2500</div>
                        </div>
                        <div class="progress progress-md">
                          <div class="progress-bar" data-progress="58"></div>
                        </div>
                      </div>
                      <div class="progress-wrap">
                        <div class="progress-text">
                          <div class="progress-label">Manufacturer</div>
                          <div class="progress-amount">2000</div>
                        </div>
                        <div class="progress progress-md">
                          <div class="progress-bar bg-orange" data-progress="50"></div>
                        </div>
                      </div>
                      <div class="progress-wrap">
                        <div class="progress-text">
                          <div class="progress-label">Exporter</div>
                          <div class="progress-amount">1600</div>
                        </div>
                        <div class="progress progress-md">
                          <div class="progress-bar bg-teal" data-progress="43"></div>
                        </div>
                      </div>
                      <div class="progress-wrap">
                        <div class="progress-text">
                          <div class="progress-label">
                            Wholesale
                          </div>
                          <div class="progress-amount">1400</div>
                        </div>
                        <div class="progress progress-md">
                          <div class="progress-bar bg-pink" data-progress="35"></div>
                        </div>
                      </div>
                      <div class="progress-wrap">
                        <div class="progress-text">
                          <div class="progress-label">
                            Trader
                          </div>
                          <div class="progress-amount">1200</div>
                        </div>
                        <div class="progress progress-md">
                          <div class="progress-bar bg-azure" data-progress="30"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-gs">
            <div class="col-md-12">
              <div class="card card-bordered">
                <div class="card-inner">
                  <div class="card-title-group">
                    <div class="card-title card-title-sm">
                      <h6 class="title">Outstanding Invoice - Party Wise (Top 10)</h6>
                    </div>
                  </div>
                  <div class="">
                    <div class="" style="height: 350px;">
                      <canvas class="" id="outstandingInvoice" ref="outstandingInvoice"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-gs">
            <div class="col-md-6">
              <div class="card card-bordered">
                <div class="card-inner">
                  <div class="card-title-group">
                    <div class="card-title card-title-sm">
                      <h6 class="title">Outstanding Commission (Supplier Wise)</h6>
                    </div>
                  </div>
                  <div class="">
                    <div class="" style="height: 350px;">
                      <canvas class="" id="outstandingCommissionSupplier" ref="outstandingCommissionSupplier"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-bordered">
                <div class="card-inner">
                  <div class="card-title-group">
                    <div class="card-title card-title-sm">
                      <h6 class="title">Outstanding Commission (Customer Wise)</h6>
                    </div>
                  </div>
                  <div class="">
                    <div class="" style="height: 350px;">
                      <canvas class="" id="outstandingCommissionCustomer" ref="outstandingCommissionCustomer"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-gs">
            <div class="col-md-6">
              <div class="card card-bordered">
                <div class="card-inner">
                  <div class="card-title-group">
                    <div class="card-title card-title-sm">
                      <h6 class="title">Product Report (Top 10)</h6>
                    </div>
                  </div>
                  <div class="">
                    <div class="" style="height: 350px;">
                      <canvas class="" id="productReport" ref="productReport"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-bordered">
                <div class="card-inner">
                  <div class="card-title-group">
                    <div class="card-title card-title-sm">
                      <h6 class="title">Fabric Report (Top 10)</h6>
                    </div>
                  </div>
                  <div class="">
                    <div class="" style="height: 350px;">
                      <canvas class="" id="fabricReport" ref="fabricReport"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'dashboard',
  data() {
    return {
      a_sm: 'assets/images/avatar/a-sm.jpg',
      b_sm: 'assets/images/avatar/b-sm.jpg',
      c_sm: 'assets/images/avatar/c-sm.jpg',
      d_sm: 'assets/images/avatar/d-sm.jpg',
    }
  },
  mounted() {
    this.initDailyCommissionInvoiceChart();
    this.initOutstangingInvoice();
    this.initOutstangingComissionSupplier();
    this.initOutstangingComissionCustomer();
    this.initProductReport();
    this.initFabricReport();
  },
  methods: {
    initDailyCommissionInvoiceChart() {
      // Access the globally available Chart object
      const ctx = this.$refs.chartDailyCommissionInvoice.getContext("2d");

      // Initialize the chart
      new Chart(ctx, {
        type: "line", // Chart type
        data: {
          labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30],
          datasets: [
            {
              label: "Commission",
              data: [
                372475, 411929, 224196, 245649, 0, 492309, 136933, 0, 359961,
                478893, 393198, 353382, 0, 0, 417270, 84354, 324989, 298414,
                202529, 466691, 271850, 172301, 123352, 0, 95139, 112831, 161282,
                89306, 395717, 492708
              ],
              borderColor: "rgba(75, 192, 192, 1)",
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              borderWidth: 2,
              tension: 0.4, // Smooth curve
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
      });
    },

    initOutstangingInvoice() {
      const ctx = this.$refs.outstandingInvoice.getContext("2d");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ['KIMORA FASHION PVT LTD', 'H.A.GARMENTS (Supplier)(NEW)', 'AL ZAYAN INTERNATIONAL', 'GULKAYRA DESIGNER', 'JAIN INDUSTRIES INC', 'SHAI BY SNEHA', 'Jay Vijay Prints Pvt.Ltd', 'SHREE RADHAKRISHNA SILK', 'Shri Ramshakti Fabrics Pvt. Ltd.', 'Shubhang Processing Mills Pvt. Ltd.'],
          datasets: [
            {
              label: "Outstanding Invoice",
              data: [372475, 411929, 324196, 245649, 290000, 250000, 250000, 354196, 100000, 150000],
              borderColor: "#f98c45",
              borderWidth: 2,
              backgroundColor: "#ffa87a",
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
          }
        },
      });
    },

    initOutstangingComissionSupplier() {
      const ctx = this.$refs.outstandingCommissionSupplier.getContext("2d");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ['Jan-2024', 'Feb-2024', 'Mar-2024', 'Apr-2024', 'May-2024', 'Jun-2024', 'Jul-2024', 'Aug-2024', 'Sep-2024', 'Oct-2024', 'Nov-2024', 'Dec-2024'],
          datasets: [
            {
              label: "Outstanding commission (Supplier)",
              data: [372475, 411929, 324196, 245649, 290000, 250000, 250000, 354196, 100000, 150000, 300000, 360000],
              borderColor: "#ff63a5",
              borderWidth: 2,
              backgroundColor: "#ffb8d6",
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
          }
        },
      });
    },

    initOutstangingComissionCustomer() {
      const ctx = this.$refs.outstandingCommissionCustomer.getContext("2d");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ['Jan-2024', 'Feb-2024', 'Mar-2024', 'Apr-2024', 'May-2024', 'Jun-2024', 'Jul-2024', 'Aug-2024', 'Sep-2024', 'Oct-2024', 'Nov-2024', 'Dec-2024'],
          datasets: [
            {
              label: "Outstanding Invoice (Customer wise)",
              data: [372475, 411929, 324196, 245649, 290000, 250000, 250000, 354196, 100000, 150000, 300000, 400000],
              borderWidth: 2,
              backgroundColor: "#b4f1ff",
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
          }
        },
      });
    },

    initProductReport() {
      const ctx = this.$refs.productReport.getContext("2d");

      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["TEBI", "D.NO86 L", "DESIGN NO-5591", "WORKE ON CHINON CHIFFON", "GREY", "DIGITAL TOP & DUPATTA", "S.VISCOSE CREAM", "RAYON VELVET", "GREY CLOTH", "Bemberg Stripes Dupatta-BLEACHED"],
          datasets: [
            {
              label: "Piece",
              data: [372475, 411929, 324196, 245649, 290000, 250000, 250000, 354196, 100000, 150000],
              borderWidth: 2,
              borderColor: "#ffdab9",
            },
            {
              label: "Meter",
              data: [14000, 15000, 8000, 100, 18000, 5000, 180000, 30, 5890, 47088],
              borderWidth: 2,
              borderColor: "#add8e6",
            }
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
            },
          }
        },
      });
    },

    initFabricReport() {
      const ctx = this.$refs.fabricReport.getContext("2d");

      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["TEBI", "D.NO86 L", "DESIGN NO-5591", "WORKE ON CHINON CHIFFON", "GREY", "DIGITAL TOP & DUPATTA", "S.VISCOSE CREAM", "RAYON VELVET", "GREY CLOTH", "Bemberg Stripes Dupatta-BLEACHED"],
          datasets: [
            {
              label: "Meter",
              data: [372475, 411929, 324196, 245649, 290000, 250000, 250000, 354196, 100000, 150000],
              borderWidth: 2,
              borderColor: "#add8e6",
            },
            {
              label: "Piece",
              data: [14000, 15000, 80000, 100, 1000, 240000, 2500, 30000, 5890, 4788],
              borderWidth: 2,
              borderColor: "#ffdab9",
            }
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
            },
          }
        },
      });
    },
  },
};
</script>
