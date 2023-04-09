<template>
  <div class="col-11 col-sm-10 col-md-6 " id="CheckOut" v-if="this.$store.state['CheckOut']['Index'] == 1">
    <font-awesome-icon class="CloseSign" icon="fa-solid fa-x" id="CloseForm"
      @click="this.$store.state['CheckOut']['Index'] = 0" />
    <h1 class="col-12">Total Selected Price : {{ this.$store.state['TheCalcResult']["PrintPrice"] + this.TheTotalFinish }}
      EGP
    </h1>
    <table class="col-12 table table-borderd">
      <tbody>
        <tr>
          <td class="col-3">Product</td>
          <th class="col-3">{{ this.$store.state['TheProducts'][this.$store.state['TheUserSelection']['ProductType']].name
          }}
          </th>
          <td class="col-3">Size</td>
          <th class="col-3">
            {{ this.$store.state['TheSizes'][this.$store.state['TheUserSelection']['ProductSize']].name == 'other' ?
              this.$store.state['TheSizes'][this.$store.state['TheUserSelection']['ProductSize']].height
              + 'x' + this.$store.state['TheSizes'][this.$store.state['TheUserSelection']['ProductSize']].width
              : this.$store.state['TheSizes'][this.$store.state['TheUserSelection']['ProductSize']].name }}
          </th>
        </tr>
        <tr>
          <td class="col-3">Paper Type</td>
          <th class="col-3">
            {{ this.$store.state['TheRawMaterial'][this.$store.state['TheUserSelection']['RawMaterial']].name }}</th>
          <td class="col-3">Paper Weight</td>
          <th class="col-3">{{ this.$store.state['TheUserSelection']['PaperWeight'] }} gsm</th>
        </tr>
        <tr>
          <td class="col-3">Printing Style</td>
          <th class="col-3">{{ this.$store.state['TheStyle'][this.$store.state['TheUserSelection']['PrintingStyle']].name
          }}
          </th>
          <td class="col-3">Color Index</td>
          <th class="col-3">{{ this.$store.state['TheUserSelection']['ColorIndex'] + 1 }} Color</th>
        </tr>
        <tr>
          <td colspan="2">Required Qty</td>
          <th colspan="2">{{ this.$store.state['TheUserSelection']['Qty'] }} pcs</th>
        </tr>
      </tbody>
    </table>
    <h1 class="col-12">Paper Deatials</h1>
    <table class="col-12 table table-borderd">
      <tbody>
        <tr>
          <td>Total Sheets</td>
          <td>{{ this.$store.state['CheckOut']['TheUserSelection']['NumberOfSheets'] }} Sheet</td>
        </tr>
        <tr>
          <td>Sheet To Peices</td>
          <td>{{ this.$store.state['TheUserSelection']['SheetToPiecesSize'] }} Peice</td>
        </tr>
        <tr>
          <td>No Of Copies Per Peice</td>
          <td>{{ this.$store.state['TheUserSelection']['NumberOfCopiesPerPiece'] }} Copy</td>
        </tr>

      </tbody>
    </table>
    <h1 class="col-12">Printing Price : {{ this.$store.state['TheCalcResult']["PrintPrice"] }} EGP</h1>
    <table class="col-12 table table-borderd">
      <tbody>
        <tr>
          <td>Paper Price</td>
          <td>{{ this.$store.state['TheCalcResult']["PaperPrice"] }} EGP</td>
        </tr>
        <tr>
          <td>CTP Price</td>
          <td>{{ this.$store.state['TheCalcResult']["CtpPrice"] }} EGP</td>
        </tr>
        <tr>
          <td>Traj Price</td>
          <td>{{ this.$store.state['TheCalcResult']["TrajPrice"] }} EGP</td>
        </tr>
      </tbody>
    </table>
    <div class="col-12 HeadCont">
      <h1 class="col-10">The Finishing Price : {{ this.TheTotalFinish }} EGP</h1>
      <font-awesome-icon icon="fa-solid fa-plus" class="CloseSign" id="AddFinish" @click="AddNewFinish()" />
    </div>
    <table class="col-12 table table-borderd">
      <tbody>
        <tr v-for="TheFinish, index in this.$store.state['TheFinishPrice']" :key="TheFinish">
          <td v-if="TheFinish['name'] != ''">{{ TheFinish['name'] }}</td>
          <td v-else>
            <select v-model="this.newFinish['value']">
              <option value='-1' hidden selected>Choose The New Finish</option>
              <option v-for="Finish, index in this.TheRemainFinish" :key="Finish" :value="index">{{ Finish['name'] }}
              </option>
            </select>
          </td>
          <td><input v-model="this.$store.state['TheFinishPrice'][index].price"> EGP</td>
          <td><font-awesome-icon icon="fa-solid fa-x" class="CloseSign" @click="RemoveFinish(index)" /></td>
        </tr>
      </tbody>
    </table>

    <hr class="col-12">
    <button class="col-3 btn btn-danger" @click="SendDataToZoho()">Approve</button>
  </div>
</template>

<script>
import { main } from '@popperjs/core';
import axios from 'axios';

export default {
  name: 'CheckOut',
  data() {
    return {
      UserSelection: {},
      PriceDetails: {},
      newFinish: {
        index: 0,
        value: -1,
      },
      TheRemainFinish: [],
    };
  },
  mounted() {
  },
  watch: {
    'newFinish.value'(newVal) {
      if (newVal != -1) {
        let finalPrice = 0;
        this.$store.state['TheFinishPrice'][this.$store.state['TheFinishPrice'].length - 1]['name'] = this.TheRemainFinish[newVal]['name'];
        let FinishPrice = this.$store.state['TheFinish'][this.$store.state['TheFinish'].findIndex(item => item.name === this.TheRemainFinish[newVal]['name'])]['Price'];
        let UnitFinsh = this.$store.state['TheFinish'][this.$store.state['TheFinish'].findIndex(item => item.name === this.TheRemainFinish[newVal]['name'])]['Unit'];
        if (UnitFinsh == 'Thousand') {
          finalPrice = FinishPrice * this.$store.state['CheckOut']['TheUserSelection']['NumberOfPicesInThousands'];
        }
        else if (UnitFinsh == 'Sheet') {
          finalPrice = FinishPrice * this.$store.state['CheckOut']['TheUserSelection']['NumberOfSheets'];
        }
        this.$store.state['TheFinishPrice'][this.$store.state['TheFinishPrice'].length - 1]['price'] = finalPrice;
        this.newFinish['index'] = 0;
        this.newFinish['value'] = -1;
      }
    }
  },
  methods: {
    RemoveFinish(index) {
      this.$store.state['TheFinishPrice'].splice(index, 1);
    },
    AddNewFinish() {
      if (this.newFinish['index'] == 0) {
        this.newFinish['index'] = 1;
        this.newFinish['value'] = -1;
        let TheObj = {
          name: "",
          price: 0,
        };
        this.$store.state['TheFinishPrice'].push(TheObj);
        this.GetRemainFinish();
      }
      else {
        alert("You Can't add more ");
      }
    },
    GetRemainFinish(Finish) {
      let main = this;
      let FinalFinish = [];
      this.$store.state['TheFinish'].forEach(Finish => { //7
        let isHere = 0;
        this.$store.state['TheFinishPrice'].forEach(SelectedFinish => {
          if (SelectedFinish['name'] == Finish['name']) {
            isHere = 1;
          }
        });
        if (isHere == 0) { FinalFinish.push(Finish); }
      });
      main.TheRemainFinish = FinalFinish;
    },
    SendDataToZoho() {
      let TotalPrice = this.$store.state['TheCalcResult']["PrintPrice"] + this.TheTotalFinish;
      let Qty = this.$store.state['TheUserSelection']['Qty'];
      let UnitPrice = TotalPrice / Qty;
      const Api_Url = this.$store.state['Api_Url'];
      axios.post(Api_Url, {
        api_name: "SendDataToZoho",
        ThePrice: UnitPrice,
      }).then(function (res) {
        alert('Done');
      });

    }
  },
  computed: {
    TheTotalFinish() {
      let Total = 0;
      this.$store.state['TheFinishPrice'].forEach(TheFinish => {
        Total = parseFloat(TheFinish['price']) + parseFloat(Total);
      });
      return Total;
    },
  },
  props: {
    msg: String
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
#CheckOut {
  display: flex;
  height: 90vh;
  z-index: 100;
  position: fixed;
  background-color: #f2f2f2;
  flex-direction: row;
  flex-wrap: wrap;
  justify-content: center;
  padding: 1rem 2rem;
  align-content: flex-start;
  align-items: center;
  overflow-y: auto;

  .CloseSign {
    background-color: #dc3545;
    border: 1px solid #dc3545;
    padding: 0.4rem 0.5rem;
    border-radius: 50%;
    color: white;
    cursor: pointer;
  }

  #AddFinish {
    background-color: #198754;
  }

  #CloseForm {
    position: absolute;
    top: 1rem;
    right: 1rem;
  }

  .CloseSign:hover {
    border: 1px solid black;
  }

  h1 {
    font-size: 1.2rem;
    padding: 1rem 0 0.4rem 0;
    font-weight: 700;
    border-bottom: 1px solid;
    margin-bottom: 0.5rem !important;
  }

  .HeadCont {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-items: center;
    justify-content: space-between;
  }
}
</style>
