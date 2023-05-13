<template>
  <CheckOut />
  <div class="col-12" id="PopUpView" v-if="this.$store.state['ShowPopUp'] == 1">
    <table class="table table-borderd col-12" v-if="this.Portrait_Copies.length > 0">
      <thead>
        <tr>
          <td>Cut Size</td>
          <td>Portrait Design</td>
          <td>price</td>
          <td>Landscape Design</td>
          <td>price</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(SheetSize, index) in this.Portrait_Copies" :key="SheetSize">
          <td>{{ SheetSize["height"] }} x {{ SheetSize["width"] }}</td>
          <td>
            <div class="TheSheet" @click="SelectOpti(
              $event,
              index,
              0,
              Landscape_Copies[index]['TotalCopies'],
              this.TotalPiecesSize[index]['SheetToPieces']
            )
              "
              :style="'height : ' + SheetSize['width'] / 10 + 'rem;' + 'width : ' + SheetSize['height'] / 10 + 'rem;'">
              <div class="TheCopy" v-for="Copy in Landscape_Copies[index]['TotalCopies']" :key="Copy" :style="'height : ' +
                UserInputs['height'] / 10 +
                'rem;' +
                'width : ' +
                UserInputs['width'] / 10 +
                'rem;'
                "></div>
            </div>
            No.Of Copies :
            {{
              this.Landscape_Copies[index]
              ? this.Landscape_Copies[index]["TotalCopies"]
              : 0
            }}
          </td>
          <td>
            {{ Landscape_Copies[index]['Price'] }}
            EGP
          </td>

          <td>
            <div v-if="this.Landscape_Copies[index]" class="TheSheet" @click="SelectOpti(
              $event,
              index,
              0,
              SheetSize['TotalCopies'],
              this.TotalPiecesSize[index]['SheetToPieces']
            )
              " :style="'height : ' +
    SheetSize['width'] / 10 +
    'rem;' +
    'width : ' +
    SheetSize['height'] / 10 +
    'rem;'
    ">
              <div class="TheCopy" v-for="Copy in SheetSize['TotalCopies']" :key="Copy" :style="'height : ' +
                UserInputs['width'] / 10 +
                'rem;' +
                'width : ' +
                UserInputs['height'] / 10 +
                'rem;'
                "></div>
            </div>
            No.Of Copies : {{ SheetSize["TotalCopies"] }}
          </td>
          <td>
            {{ SheetSize['Price'] }}
            EGP
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
import CheckOut from '@/components/CheckOut.vue'
export default {
  name: "PopUpView",
  components: { CheckOut },
  data() {
    return {
      UserInputs: {
        width: this.$store.state["TheSizes"][
          this.$store.state["TheUserSelection"]["ProductSize"]
        ].width,
        height: this.$store.state["TheSizes"][
          this.$store.state["TheUserSelection"]["ProductSize"]
        ].height,
      },
      Portrait_Copies: [],
      Landscape_Copies: [],
      TheWeights: [130, 170, 200, 250, 300, 350],
      TheUserSelection: this.$store.state["TheUserSelection"],
      AllProducts: this.$store.state["AllProducts"],
      TheTypes: this.$store.state["TheTypes"],
      TheProducts: this.$store.state["TheProducts"],
      TheSizes: this.$store.state["TheSizes"],
      TotalPiecesSize: this.$store.state["TotalPiecesSize"],
      PiecesSize: this.$store.state["PiecesSize"].sort(function (a, b) {
        return b.height - a.height;
      }),
      TheStyle: this.$store.state["TheStyle"],
      TheFinish: this.$store.state["TheFinish"],
      TheRawMaterial: this.$store.state["TheRawMaterial"],
      TheCTPMaterial: this.$store.state["TheCTPMaterial"],
      TheTrajMaterial: this.$store.state["TheTrajMaterial"],
      TheCalcResult: this.$store.state["TheCalcResult"],
      TheFinishPrice: this.$store.state["TheFinishPrice"],
    };
  },
  created() {
    let main = this;
    if (main.$store.state['ShowPopUp'] == 1) {
      this.GetExpectedSizes(this.UserInputs["height"], this.UserInputs["width"], 0);
      this.GetExpectedSizes(this.UserInputs["width"], this.UserInputs["height"], 1);
      let i = -1;
      let x = -1;
      main.Portrait_Copies.forEach(Size => {
        i = i + 1;
        let price = main.CalculateThePrice(Size['SheetToPieces'], Size['TotalCopies'], Size['Cat'], i);
        Size['Price'] = price;
      });
      main.Landscape_Copies.forEach(Size => {
        x = x + 1;
        let price = main.CalculateThePrice(Size['SheetToPieces'], Size['TotalCopies'], Size['Cat'], x);
        Size['Price'] = price;
      });
    }
  },
  methods: {
    GetExpectedSizes(TheHeight, TheWidth, Index) {
      let main = this;
      let Portrait_Copies = [];
      let Landscape_Copies = [];
      let Object = {};
      this.PiecesSize.forEach((Size) => {
        Object = {
          height: Size["height"],
          width: Size["width"],
          SheetToPieces: Size["SheetToPieces"],
          SheetType: Size["SheetType"],
          CTPID: Size["CTPID"],
          TrajID: Size["TrajID"],
          Cat: Size["Cat"],
          CopiesPerWidth: 0,
          CopiesPerHeight: 0,
          TotalCopies: 0,
        };
        if (TheHeight < Size["height"]) {
          if (TheWidth < Size["width"]) {
            if (Size["width"] / TheWidth > 1) {
              Object["CopiesPerWidth"] = 1;
              if (Size["width"] / TheWidth > 2) {
                Object["CopiesPerWidth"] = 2;
                if (Size["width"] / TheWidth > 3) {
                  Object["CopiesPerWidth"] = 3;
                  if (Size["width"] / TheWidth > 4) {
                    Object["CopiesPerWidth"] = 4;
                    if (Size["width"] / TheWidth > 5) {
                      Object["CopiesPerWidth"] = 5;
                      if (Size["width"] / TheWidth > 6) {
                        Object["CopiesPerWidth"] = 6;
                        if (Size["width"] / TheWidth > 7) {
                          Object["CopiesPerWidth"] = 7;
                          if (Size["width"] / TheWidth > 8) {
                            Object["CopiesPerWidth"] = 8;
                            if (Size["width"] / TheWidth > 9) {
                              Object["CopiesPerWidth"] = 9;
                              if (Size["width"] / TheWidth > 10) {
                                Object["CopiesPerWidth"] = 10;
                                if (Size["width"] / TheWidth > 11) {
                                  Object["CopiesPerWidth"] = 11;
                                  if (Size["width"] / TheWidth > 12) {
                                    Object["CopiesPerWidth"] = 12;
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }

            if (Size["height"] / TheHeight > 1) {
              Object["CopiesPerHeight"] = 1;
              if (Size["height"] / TheHeight > 2) {
                Object["CopiesPerHeight"] = 2;
                if (Size["height"] / TheHeight > 3) {
                  Object["CopiesPerHeight"] = 3;
                  if (Size["height"] / TheHeight > 4) {
                    Object["CopiesPerHeight"] = 4;
                    if (Size["height"] / TheHeight > 5) {
                      Object["CopiesPerHeight"] = 5;
                      if (Size["height"] / TheHeight > 6) {
                        Object["CopiesPerHeight"] = 6;
                        if (Size["height"] / TheHeight > 7) {
                          Object["CopiesPerHeight"] = 7;
                          if (Size["height"] / TheHeight > 8) {
                            Object["CopiesPerHeight"] = 8;
                            if (Size["height"] / TheHeight > 9) {
                              Object["CopiesPerHeight"] = 9;
                              if (Size["height"] / TheHeight > 10) {
                                Object["CopiesPerHeight"] = 10;
                                if (Size["height"] / TheHeight > 11) {
                                  Object["CopiesPerHeight"] = 11;
                                  if (Size["height"] / TheHeight > 12) {
                                    Object["CopiesPerHeight"] = 12;
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
            Object["TotalCopies"] = Object["CopiesPerWidth"] * Object["CopiesPerHeight"];
          }
        }
        if (Index == 0) {
          Portrait_Copies.push(Object);
        } else {
          Landscape_Copies.push(Object);
        }
      });
      if (Index == 0) {
        Portrait_Copies = Portrait_Copies.sort(function (a, b) {
          return b.height - a.height;
        });
        main.Portrait_Copies = Portrait_Copies;
      } else {
        Landscape_Copies = Landscape_Copies.sort(function (a, b) {
          return b.height - a.height;
        });
        main.Landscape_Copies = Landscape_Copies;
      }
    },
    CalculateThePrice(SheetToPieces, CopiesInOnePiece, SheetType, Index) {
      let main = this;
      // Sheet --> Piece --> Copies [Paper Price]
      let PricePerTon = main.TheRawMaterial[main.TheUserSelection["RawMaterial"]].Price;
      let PricePerGram = PricePerTon / 1000000;
      let RequiredQty = main.TheUserSelection["Qty"];
      // let SheetToPieces = main.PiecesSize[main.TheUserSelection['SheetToPiecesSize']].SheetToPieces;
      // let SheetType = main.PiecesSize[main.TheUserSelection['SheetToPiecesSize']].Cat;
      // let CopiesInOnePiece = main.TheUserSelection['NumberOfCopiesPerPiece'];
      let NumberOfPieces = Math.ceil(RequiredQty / CopiesInOnePiece);
      let NumberOfSheets = Math.ceil(NumberOfPieces / SheetToPieces);
      let NumberOfPicesInThousands = Math.ceil(NumberOfPieces / 1000);
      let SheetSize = parseFloat(1 * 0.7);
      if (SheetType != "Standard") {
        SheetSize = parseFloat(0.66 * 0.88);
      }
      let SheetWeight = main.TheUserSelection["PaperWeight"];
      //console.log('PricePerGram : ' + PricePerGram + ' & SheetSize : ' + SheetSize + ' & NumberOfSheets : ' + NumberOfSheets + ' & SheetWeight : ' + SheetWeight);
      let PaperPrice = Math.ceil(PricePerGram * SheetSize * NumberOfSheets * SheetWeight);
      let ColorIndex = main.TheUserSelection["ColorIndex"] + 1;
      // console.log(ColorIndex);
      // CTP Price
      let CTPID = main.PiecesSize[Index].CTPID;
      let RawCTP =
        main.TheCTPMaterial[
          main.TheCTPMaterial.findIndex(function (item) {
            return item.id == CTPID;
          })
        ].Price;
      let FinalCTP = Math.ceil(RawCTP * ColorIndex);
      // console.log(FinalCTP);
      //Printing Price
      let TrajID = main.PiecesSize[Index].TrajID;
      let RawTraj =
        main.TheTrajMaterial[
          main.TheTrajMaterial.findIndex(function (item) {
            return item.id == TrajID;
          })
        ].Price;
      let faceIndex = main.TheUserSelection["PrintingStyle"] + 1;
      let FinalPrintingPrice = Math.ceil(
        NumberOfPicesInThousands * faceIndex * ColorIndex * RawTraj
      );
      // if (FinalPrintingPrice != 'Infinity') {
      // console.log(main.PiecesSize);
      // }
      let FinalPrice = PaperPrice + FinalCTP + FinalPrintingPrice;
      main.TheCalcResult["PaperPrice"] = PaperPrice;
      main.TheCalcResult["CtpPrice"] = FinalCTP;
      main.TheCalcResult["TrajPrice"] = FinalPrintingPrice;
      // alert(FinalPrice);
      //After Printing
      let TheFinishPrice = [];
      main.TheFinish.forEach((finish) => {
        if (finish["Selected"] == true) {
          let Total = 0;
          if (finish["Unit"] == "Sheet") {
            Total = NumberOfSheets * finish["Price"];
          } else if (finish["Unit"] == "Thousand") {
            Total = NumberOfPicesInThousands * finish["Price"];
          }
          let TheObject = {
            name: finish["name"],
            ArabicName: finish["ArabicName"],
            price: Total,
          };
          TheFinishPrice.push(TheObject);
        }
      });
      main.TheFinishPrice = TheFinishPrice;
      //Finishing + Triming
      // main.ShowCalc = 1;
      let TheFinalPrice = FinalPrice + main.TotalFishPrice;
      if (TheFinalPrice == "Infinity") {
        TheFinalPrice = 0;
      }
      return TheFinalPrice;
    },
    CalcSelected() {
      let main = this;
      let UserSelection = main.$store.state['TheUserSelection'];
      let PricePerTon = main.$store.state['TheRawMaterial'][UserSelection["RawMaterial"]].Price;
      let PricePerGram = PricePerTon / 1000000;
      let RequiredQty = UserSelection["Qty"];
      let CopiesInOnePiece = UserSelection['NumberOfCopiesPerPiece'];
      let NumberOfPieces = Math.ceil(RequiredQty / CopiesInOnePiece);
      let SheetToPieces = UserSelection['SheetToPiecesSize'];
      let NumberOfSheets = Math.ceil(NumberOfPieces / SheetToPieces);
      let NumberOfPicesInThousands = Math.ceil(NumberOfPieces / 1000);
      let SheetType = main.$store.state['TotalPiecesSize'][UserSelection['PieceSizeIndex']].Cat;
      let SheetSize = parseFloat(1 * 0.7);
      if (SheetType != "Standard") {
        SheetSize = parseFloat(0.66 * 0.88);
      }
      let SheetWeight = UserSelection["PaperWeight"];
      let PaperPrice = Math.ceil(PricePerGram * SheetSize * NumberOfSheets * SheetWeight);
      let ColorIndex = UserSelection["ColorIndex"] + 1;
      let CTPID = main.$store.state['PiecesSize'][UserSelection['PieceSizeIndex']].CTPID;
      let RawCTP =
        main.$store.state['TheCTPMaterial'][
          main.$store.state['TheCTPMaterial'].findIndex(function (item) {
            return item.id == CTPID;
          })
        ].Price;
      let FinalCTP = Math.ceil(RawCTP * ColorIndex);
      let TrajID = main.$store.state['PiecesSize'][UserSelection['PieceSizeIndex']].TrajID;
      let RawTraj =
        main.$store.state['TheTrajMaterial'][
          main.$store.state['TheTrajMaterial'].findIndex(function (item) {
            return item.id == TrajID;
          })
        ].Price;
      let faceIndex = UserSelection["PrintingStyle"] + 1;
      let FinalPrintingPrice = Math.ceil(
        NumberOfPicesInThousands * faceIndex * ColorIndex * RawTraj
      );
      let FinalPrice = PaperPrice + FinalCTP + FinalPrintingPrice;
      main.$store.state['TheCalcResult']["PaperPrice"] = PaperPrice;
      main.$store.state['TheCalcResult']["CtpPrice"] = FinalCTP;
      main.$store.state['TheCalcResult']["TrajPrice"] = FinalPrintingPrice;
      main.$store.state['TheCalcResult']["PrintPrice"] = FinalPrice;
      main.$store.state['CheckOut']['TheUserSelection']['NumberOfSheets'] = NumberOfSheets;
      main.$store.state['CheckOut']['TheUserSelection']['NumberOfPicesInThousands'] = NumberOfPicesInThousands;
      let TheFinishPrice = [];
      main.$store.state['TheFinish'].forEach((finish) => {
        if (finish["Selected"] == true) {
          let Total = 0;
          if (finish["Unit"] == "Sheet") {
            Total = NumberOfSheets * finish["Price"];
          } else if (finish["Unit"] == "Thousand") {
            Total = NumberOfPicesInThousands * finish["Price"];
          }
          let TheObject = {
            name: finish["name"],
            ArabicName: finish["ArabicName"],
            price: Total,
          };
          TheFinishPrice.push(TheObject);
        }
      });
      main.$store.state['TheFinishPrice'] = TheFinishPrice;
    },
    SelectOpti(e, index, dir, NoOfCopies, SheetToPieces) {
      let main = this;
      let TheClass = $(e.target).attr("class");
      $(".TheSheet").removeClass("Selected");
      if (TheClass == "TheSheet" && $(e.target).children("TheCopy").length != 0) {
        $(e.target).addClass("Selected");
      } else if (TheClass == "TheCopy") {
        $(e.target).parent().addClass("Selected");
      }
      // if (dir == 0) {
      //   alert(
      //     index +
      //     " : Portrait" +
      //     "- Copies : " +
      //     NoOfCopies +
      //     " Sheet to Pices" +
      //     SheetToPieces
      //   );
      // } else {
      //   alert(
      //     index +
      //     " : Landscape" +
      //     "- Copies : " +
      //     NoOfCopies +
      //     " Sheet to Pices" +
      //     SheetToPieces
      //   );
      // }


      main.$store.state['TheUserSelection']['SheetToPiecesSize'] = SheetToPieces;
      main.$store.state['TheUserSelection']['NumberOfCopiesPerPiece'] = NoOfCopies;
      main.$store.state['TheUserSelection']['PieceSizeIndex'] = index;
      main.$store.state["CheckOut"]["Index"] = 1;
      main.CalcSelected();
    },
  },
  watch: {},
  computed: {
    TotalFishPrice() {
      let TheTotal = 0;
      this.TheFinishPrice.forEach((finish) => {
        TheTotal = TheTotal + parseFloat(finish["price"]);
      });
      return parseFloat(TheTotal);
    },
  },
};
</script>

<style lang="scss" scoped>
#PopUpView {
  direction: ltr;
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  padding: 1rem;
  background-color: #f2f2f2;
  overflow-x: auto;
  overflow-y: auto;

  table {
    vertical-align: middle;
  }

  .TheSheet {
    display: flex;
    background-color: white;
    flex-direction: row;
    flex-wrap: wrap;
    outline: 1px solid rgba(128, 128, 128, 0.23);
    align-content: center;
    justify-content: center;
    align-items: center;
  }

  .TheCopy {
    background-color: rgba(156, 181, 213, 0.4);
    border: inset 1px #b6b6b669;
  }

  .Selected {
    outline: 1px solid #105778;
    box-shadow: 3px 4px 8px black;
  }
}
</style>
