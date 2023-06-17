<template>
  <div class="col-12" id="TasksView">
    <div class="col-12" v-if="this.$store.state['Price_Tasks'].length == 0" id="NoTasks">
      <img src="@/assets/NoTasks.png" />
      <h1>لا يوجد مهمات تسعير جديدة</h1>
    </div>

    <div class="col-12 TasksDiv" v-else>
      <h1
        class="col-12 TabHeader"
        style="font-size: 1.5rem; font-weight: 700; text-align: center"
      >
        طلبات التسعير
      </h1>
      <div
        class="col-12 Task_Card"
        v-for="(PriceTask, index) in this.$store.state['Price_Tasks']"
        :key="PriceTask"
      >
        <table class="col-12 table table-bordered">
          <tbody>
            <tr>
              <th>اسم الطلب</th>
              <td>{{ PriceTask["Name"] }}</td>
            </tr>
            <tr>
              <th>تفاصيل الطلب</th>
              <td>
                {{ PriceTask["Requirement_Details"] }}
              </td>
            </tr>
            <tr>
              <th>تاريخ الطلب</th>
              <td>{{ PriceTask["Created_Date"] }}</td>
            </tr>
            <tr>
              <th>عدد القطع</th>
              <td>{{ PriceTask["Quantity"] }}</td>
            </tr>
            <tr>
              <th>الصور المرفقة</th>
              <td>
                <button
                  v-if="!PriceTask['Attachments'] && PriceTask['Attachments'] != 0"
                  class="btn btn-success"
                  @click="this.Show_Task_Attachments(PriceTask['Task_ID'], index)"
                >
                  عرض المرفقات
                </button>
                <p v-else-if="Number(PriceTask['Attachments']) == 0">
                  لاتوجد مرفقات لهذه المهمة
                </p>
                <div class="col-12" v-else>
                  <img
                    @click="
                      this.Show_Image = 1;
                      this.Manual_Price_PopUp = 1;
                      Image_Src = this.Api_Url.split('index.php')[0] + Attach_Url;
                    "
                    class="AttachImg-small"
                    v-for="Attach_Url in PriceTask['Attachments']"
                    :key="Attach_Url"
                    :src="this.Api_Url.split('index.php')[0] + Attach_Url"
                  />
                </div>
              </td>
            </tr>
            <tr>
              <th>اسم المسئول</th>
              <td>{{ PriceTask["Deal_Owner"] }}</td>
            </tr>
            <tr>
              <!-- <th>طريقة التسعير </th> -->
              <td colspan="2">
                <button
                  class="btn btn-primary col-12"
                  @click="
                    this.SelectPricingWay(PriceTask['Task_ID'], index);
                    this.PricingWay = 1;
                  "
                >
                  تسعير الطلب
                </button>
                <!-- <select class="form-select" v-model="this.PricingWay"
                                    @change="this.SelectPricingWay(PriceTask['Task_ID'], index)">
                                    <option value="0" hidden disabled>اختار الطريقة</option>
                                    <option value="1">يدوي</option>
                                    <option value="2">اوتوماتيك</option>
                                </select> -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- <table class="col-12 table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>-</th>
                        <th class="col-3">اسم المهمة</th>
                        <th v-if=" this.$store.state['CurrentWidth'] > 767 ">تفاصيل المهمة</th>
                        <th>التسليم في</th>
                        <th v-if=" this.$store.state['CurrentWidth'] > 767 ">عدد القطع</th>
                        <th>طريقة التسعير</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for=" PriceTask, index  in  this.$store.state['Price_Tasks'] " :key=" PriceTask ">
                        <td>{{ index + 1 }}</td>
                        <td>{{ PriceTask['Name'] }}</td>
                        <td v-if=" this.$store.state['CurrentWidth'] > 767 ">
                            <pre>{{
                                PriceTask['Requirement_Details'] }}</pre>
                        </td>
                        <td>{{ PriceTask['Created_Date'] }}</td>
                        <td v-if=" this.$store.state['CurrentWidth'] > 767 ">{{ PriceTask['Quantity'] }}</td>
                        <td>
                            <select class="form-select" v-model=" this.PricingWay "
                                @change=" this.SelectPricingWay(PriceTask['Task_ID'], index) ">
                                <option value="0" hidden disabled>اختار الطريقة</option>
                                <option value="1">يدوي</option>
                                <option value="2">اوتوماتيك</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table> -->
    </div>

    <div
      id="ManualPrice"
      v-if="this.Manual_Price_PopUp == 1"
      @click="
        this.Manual_Price_PopUp = 0;
        this.PricingWay = 0;
        this.Show_Image = 0;
      "
    >
      <img
        v-if="this.Show_Image == 1"
        @click="$event.stopPropagation()"
        :src="this.Image_Src"
        class="col-11 col-md-9 col-lg-6"
      />
      <div
        class="col-11 col-md-9 col-lg-6"
        @click="$event.stopPropagation()"
        id="TheBox"
        v-else
      >
        <table class="col-12 table table-bordered">
          <tbody>
            <!-- <tr>
                            <th>اسم الطلب</th>
                            <td>{{ this.$store.state['Price_Tasks'][Open_Task_Index]['Name'] }}</td>
                        </tr>
                        <tr>
                            <th>تفاصيل الطلب</th>
                            <td>
                                <p>{{ this.$store.state['Price_Tasks'][Open_Task_Index]['Requirement_Details'] }}</p>
                            </td>
                        </tr> -->
            <tr>
              <th>عدد القطع</th>
              <td>{{ this.$store.state["Price_Tasks"][Open_Task_Index]["Quantity"] }}</td>
            </tr>
            <!-- <tr>
                            <th>ميعاد الطلب</th>
                            <td>{{ this.$store.state['Price_Tasks'][Open_Task_Index]['Created_Date'] }}</td>
                        </tr> -->
            <tr>
              <th>سعر القطعة</th>
              <td>
                <input
                  type="number"
                  v-model="this.FinalManualPrice"
                  placeholder="ادخل سعر القطعة"
                />
              </td>
            </tr>
          </tbody>
        </table>

        <button
          class="btn btn-success"
          @click="UpdatePriceManual(this.Open_Task, this.FinalManualPrice)"
        >
          سجل السعر
        </button>
      </div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
export default {
  name: "TasksView",
  components: {},
  data() {
    return {
      Api_Url: this.$store.state["Api_Url"],
      PricingWay: 0,
      Open_Task: 0,
      Open_Task_Index: -1,
      FinalManualPrice: null,
      Manual_Price_PopUp: 0,
      Show_Image: 0,
      Image_Src: "",
    };
  },
  created() {
    let main = this;
    // console.log(this.$store.state['Price_Tasks']);
  },
  methods: {
    SelectPricingWay(Task_ID, Task_Index) {
      if (this.PricingWay == 2) {
        this.OpenTask(Task_ID);
      } else {
        this.Open_Task = Task_ID;
        this.Manual_Price_PopUp = 1;
        this.Open_Task_Index = Task_Index;
        // console.log(this.$store.state['Price_Tasks'][this.Open_Task_Index]);
      }
    },
    OpenTask(Task_ID) {
      let main = this;
      main.$store.state["TheTaskID"] = Task_ID;
      axios
        .post(this.Api_Url, {
          api_name: "SaveTaskId",
          the_code: Task_ID,
        })
        .then(function (res) {
          main.$store.state["CurrentComponent"] = "FinalView";
          main.$store.state["RunApi"] = 1;
        });
    },
    UpdatePriceManual(Price_Task_ID, Final_Unit_Price) {
      let main = this;
      if (Final_Unit_Price == "" || Final_Unit_Price == 0 || Final_Unit_Price == null) {
        alert("من فضلك ادخل السعر اولاً");
      } else {
        this.$swal
          .fire({
            title: "هل تريد ارسال السعر للقطعة الواحدة = " + Final_Unit_Price + " جنيه ؟",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ارسال",
            cancelButtonText: "ليس الأن",
          })
          .then((result) => {
            if (result.isConfirmed) {
              main.$store.state["LoaderIndex"] = 1;
              axios
                .post(main.Api_Url, {
                  api_name: "SendDataToZoho",
                  Task_ID: Price_Task_ID,
                  ThePrice: Final_Unit_Price,
                })
                .then(function (res) {
                  main.$store.state["LoaderIndex"] = 0;
                  main.$swal.fire("تم انهاء المهمة بنجاح");
                  location.reload();
                });
            } else {
              main.$store.state["LoaderIndex"] = 0;
            }
          });
      }
    },
    Show_Task_Attachments(Task_ID, index) {
      this.$store.state["LoaderIndex"] = 1;
      let main = this;
      axios
        .post(this.Api_Url, {
          api_name: "GetRecordAttachements",
          Record_ID: Task_ID,
          Module_Name: "Price_Tasks",
        })
        .then(function (res) {
          // console.log(res.data);
          if (res.data) {
            main.$store.state["Price_Tasks"][index]["Attachments"] = res.data;
          } else {
            main.$store.state["Price_Tasks"][index]["Attachments"] = 0;
          }
          main.$store.state["LoaderIndex"] = 0;
        });
    },
  },
  watch: {},
  computed: {},
};
</script>

<style lang="scss" scoped>
#TasksView {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  align-content: flex-start;
  justify-content: center;
  align-items: center;
  padding: 1rem;

  #NoTasks {
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    align-items: center;
    height: 100%;
    justify-content: center;

    h1 {
      font-size: 2rem;
      text-align: center;
    }

    img {
      width: 10rem;
      margin-bottom: 1rem;
    }
  }
  .AllAttachments-Div {
    display: flex;
  }
  .AttachImg-small {
    height: 5rem;
    cursor: pointer;
    margin: 0 0.5rem;
    box-shadow: 0;
    transition: ease all 400ms;
  }
  .AttachImg-small:hover {
    box-shadow: 1px 1px 1px;
  }
  .TasksDiv {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-start;
    align-items: center;
  }

  .TabHeader {
    font-size: 1.4rem;
    padding: 1rem 0;
  }

  table {
    font-size: 1.3rem;
    text-align: center;
    vertical-align: middle;
    margin: 0;

    th {
      font-size: 1rem;
    }

    th,
    td {
      padding: 0.7rem 0.5rem;
    }

    select {
      font-size: 1.3rem;
    }

    button {
      font-size: 1.3rem;
      padding: 0.5rem;
    }
  }

  #ManualPrice {
    height: 100vh;
    width: 100%;
    left: 0;
    top: 0;
    background: rgba(0, 0, 0, 0.568627451);
    position: fixed;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: center;
    justify-content: center;
    align-items: center;
    overflow: auto;
  }
}

#TheBox {
  background-color: white;
  padding: 1rem;
  display: flex;
  flex-direction: column;

  h1 {
    font-size: 1.2rem;
    margin-bottom: 1rem !important;
  }

  button {
    font-size: 1.2rem;
    padding: 0.5rem;
  }

  input {
    font-size: 1.2rem;
    padding: 0.5rem;
  }
}

pre {
  max-width: 90%;
  overflow: auto;
  font-size: 1.3rem;
  font-weight: 500;
  margin: 0 !important;
}

.Task_Card {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  align-content: center;
  justify-content: center;
  align-items: center;
  background-color: white;
  margin-bottom: 1rem;
  padding: 0.5rem;
  box-shadow: 1px 1px 1px grey;
  border: solid 1px;
  border-radius: 0.25rem;
}
</style>
