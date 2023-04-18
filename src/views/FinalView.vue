<template>
    <div class="col-12" id="FinalView" :dir="this.$store.state['UserLang'] == 1 ? 'ltr' : 'rtl'">
        <div class="col-12 PageHeader">
            <div class="AppInfo">
                <img src="@/assets/photos/priceing.png">
                <h1>{{ this.$store.state['UserLang'] == 1 ? 'Pricing Gate' : 'قائمة تسعير المنتجات' }}</h1>
            </div>
            <div class="SelectLang" v-if="this.$store.state['CurrentWidth'] > 767">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
                        v-model="this.UserLang">
                    <label class="form-check-label" for="flexSwitchCheckDefault">اللغة العربية</label>
                </div>
            </div>
            <font-awesome-icon icon="fa-solid fa-bars" class="SideMenuBtn"
                v-if="this.$store.state['CurrentWidth'] <= 767" />
            <div class="col-12 SideMenu">

            </div>
        </div>
        <div class="col-12 col-md-9 PageContent">
            <!-- RequestType -->
            <div class="col-12 col-sm-6 SelectField">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select The Request Type' : 'اختار نوع الطلب' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['RequestType']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? 'The Request Type' : 'نوع الطلب' }}
                    </option>
                    <option :value="index" v-for="type, index in TheTypes" :key="type">
                        {{ this.$store.state['UserLang'] == 1 ? type['name'] : type['ArabicName'] }}
                    </option>
                </select>
            </div>

            <!-- ProductType -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['RequestType'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select The Product' : 'اختار اسم المنتج' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['ProductType']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? 'The Product' : 'اسم المنتج' }}
                    </option>
                    <option :value="index" v-for="type, index in TheProducts" :key="type">
                        {{ this.$store.state['UserLang'] == 1 ? type['name'] : type['ArabicName'] }}
                    </option>
                </select>
            </div>

            <!-- RawMaterial -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['ProductType'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select The Printing Material' : 'اختار خامة الطباعة' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['RawMaterial']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? 'The Raw material' : 'خامة الورق' }}
                    </option>
                    <option :value="index" v-for="type, index in TheRawMaterial" :key="type">
                        {{ this.$store.state['UserLang'] == 1 ? type['name'] : type['ArabicName'] }}
                    </option>
                </select>
            </div>

            <!-- PaperWeight -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['RawMaterial'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Paper Sheet Weight' : 'وزن خامة الورق' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['PaperWeight']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? 'The paper weight' : 'وزن الورق' }}
                    </option>
                    <option :value="Size" v-for="Size in this.TheWeights" :key="Size">
                        {{ Size }}
                    </option>
                </select>
            </div>

            <!-- ProductSize -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['PaperWeight'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select The Product Size' : 'اختار مقاس المنتج' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['ProductSize']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? 'The Size' : 'مقاس المنتج' }}
                    </option>
                    <option :value="index" v-for="Size, index in TheSizes" :key="Size">
                        {{ Size['name'] }} ( W:{{ Size['width'] }} cm x H:{{ Size['height'] }} cm )
                    </option>
                </select>
                <div class="col-12 FlexCenter"
                    v-if="this.TheUserSelection['ProductSize'] == this.TheSizes.findIndex(item => item.name === 'other')">
                    <div class="col-5">
                        <label class="col-12">{{ this.$store.state['UserLang'] == 1 ? 'Required Width' : "العرض المطلوب"
                        }}</label>
                        <input class="col-12" :placeholder="this.$store.state['UserLang'] == 1 ? 'The width' : 'العرض'"
                            v-model="this.TheSizes[this.TheSizes.findIndex(item => item.name === 'other')].width">
                    </div>

                    <div class="col-5">
                        <label class="col-12">{{ this.$store.state['UserLang'] == 1 ? 'Required Height' : "الطول المطلوب"
                        }}</label>
                        <input class="col-12" :placeholder="this.$store.state['UserLang'] == 1 ? 'The height' : 'الطول'"
                            v-model="this.TheSizes[this.TheSizes.findIndex(item => item.name === 'other')].height">
                    </div>
                </div>
            </div>
            <!-- PrintingStyle -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['ProductSize'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select Printing Style' : 'اختار شكل الطباعة' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['PrintingStyle']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? 'Printing Style' : 'شكل الطباعة' }}
                    </option>
                    <option :value="index" v-for="type, index in TheStyle" :key="type">
                        {{ this.$store.state['UserLang'] == 1 ? type['name'] : type['ArabicName'] }}
                    </option>
                </select>
            </div>

            <!-- ColorIndex -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['PrintingStyle'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select Color Style' : 'اختار عدد الالوان في الطباعة' }}
                </label>
                <select class="col-12" v-model="this.TheUserSelection['ColorIndex']">
                    <option value="-1" hidden selected>
                        {{ this.$store.state['UserLang'] == 1 ? ' Color Index ' : 'عدد الألوان' }}
                    </option>
                    <option :value="index" v-for="type, index in 4" :key="type">
                        {{ this.$store.state['UserLang'] == 1 ? type + ' Color' : type + ' لون' }}
                    </option>
                </select>
            </div>

            <!-- The Finishing -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['ColorIndex'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Select Product Finshing' : 'اختار محلقات ما بعد الطباعة' }}
                </label>
                <div class="Finish col-12">
                    <div class="col-5 col-sm-4 col-md-3" v-for="Finish, index in TheFinish" :key="Finish">
                        <input :id="'Finish_' + index" type="checkbox" v-model="TheFinish[index].Selected">
                        <label :for="'Finish_' + index">{{ this.$store.state['UserLang'] == 1 ? Finish.name :
                            Finish.ArabicName
                        }}</label>
                    </div>
                </div>
            </div>

            <!-- Required Qty -->
            <div class="col-12 col-sm-6 SelectField" v-if="this.TheUserSelection['ColorIndex'] != -1">
                <label class="col-12">
                    {{ this.$store.state['UserLang'] == 1 ? 'Required Quantatiy' : 'الكمية المطلوبة' }}
                </label>
                <input class="col-12" v-model="this.TheUserSelection['Qty']">
            </div>
        </div>
        <div class="col-12 col-md-3 TaskDetails">
            <h1 class="col-12">Request Details</h1>
            <pre class="col-12">{{ this.$store.state['TheTaskDetails'] }}</pre>
            <p class="col-12">Qty : {{ this.TheUserSelection['Qty'] }}</p>
        </div>
        <div class="col-12 PageFooter" v-if="this.TheUserSelection['Qty'] != 0">
            <button class="btn btn-success"
                @click="this.$store.state['ShowPopUp'] = 1; this.UpdateData();">Calculate</button>
        </div>
        <div class="col-12 PageCalc" v-if="this.ShowCalc == 1">
            <table class="table  table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Paper Price</th>
                        <th>CTP Price</th>
                        <th>Printing Price</th>
                        <th v-if="this.TheFinishPrice.length != 0">Finish Price [{{ this.TotalFishPrice }}]</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ this.TheCalcResult['PaperPrice'] }}</td>
                        <td>{{ this.TheCalcResult['CtpPrice'] }}</td>
                        <td>{{ this.TheCalcResult['TrajPrice'] }}</td>
                        <td v-if="this.TheFinishPrice.length != 0">
                            <div class="FinishContainer" v-for="finish in this.TheFinishPrice" :key="finish">
                                <span>{{ finish['name'] }} : </span>
                                <input v-model="finish['price']" class="ThePrice">
                            </div>
                        </td>
                        <td>{{ parseFloat(this.TheCalcResult['PaperPrice']) + parseFloat(this.TheCalcResult['CtpPrice']) +
                            parseFloat(this.TheCalcResult['TrajPrice']) + parseFloat(this.TotalFishPrice) }} </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12 PopUpPage" v-if="this.$store.state['ShowPopUp'] == 1"
            @click="this.$store.state['ShowPopUp'] = 0">
            <div class="col-11 col-sm-10 col-md-6 TheContent" @click="$event.stopPropagation();">
                <PopUpView />
            </div>
        </div>
    </div>
</template>
  
<script>
// @ is an alias to /src
import PopUpView from '@/views/PopUpView.vue';
import axios from 'axios';
export default {
    name: 'FinalView',
    components: { PopUpView },
    data() {
        return {
            RunApi: this.$store.state['RunApi'],
            UserLang: 0,
            ShowCalc: 0,
            ShowPopUp: 0,
            TheWeights: [130, 170, 200, 250, 300, 350],
            TheUserSelection: {
                RequestType: -1,
                ProductType: -1,
                ProductSize: -1,
                PrintingStyle: -1,
                Qty: 0,
                RawMaterial: -1,
                ColorIndex: -1,
                Finishing: -1,
                SheetToPiecesSize: -1,
                NumberOfCopiesPerPiece: 0,
                PaperWeight: -1,
            },
            AllProducts: [],
            TheTypes: [],
            TheProducts: [],
            TheSizes: [{
                name: "A1",
                width: 59.5,
                height: 84.1,
            }, {
                name: "A2",
                width: 42,
                height: 59.4,
            },
            {
                name: "A3",
                width: 29.7,
                height: 42,
            },
            {
                name: "A4",
                width: 21,
                height: 29.7,
            },
            {
                name: "A5",
                width: 14.8,
                height: 21,
            },
            {
                name: "A6",
                width: 10.5,
                height: 14.8,
            },
            {
                name: "A7",
                width: 7.4,
                height: 10.5,
            },
            {
                name: "other",
                width: 0,
                height: 0,
            }
            ],
            TotalPiecesSize: [],
            PiecesSize: [],
            TheStyle: [
                {
                    name: "One face",
                    ArabicName: "وجه واحد"
                },
                {
                    name: "Dobule face",
                    ArabicName: "وجهان"
                }
            ],
            TheFinish: [],
            TheRawMaterial: [],
            TheCTPMaterial: [],
            TheTrajMaterial: [],
            TheCalcResult: {
                PaperPrice: 0,
                CtpPrice: 0,
                TrajPrice: 0,
            },
            TheFinishPrice: [],
        };
    },
    created() {
        this.$store.state['LoaderIndex'] = 1;
        if (this.RunApi == 1) {
            let main = this;
            main.ExcuteApis();
        }
    },
    methods: {
        GetStandardCut(Index) {
            let main = this;
            let NewArray = [];
            if (Index == 0) {
                main.PiecesSize = main.TotalPiecesSize;
            }

            else if (Index == 1) {
                main.TotalPiecesSize.forEach(Size => {
                    if (Size['Cat'] == "Standard") {
                        NewArray.push(Size);
                    }
                });
                main.PiecesSize = NewArray;
            }
            else if (Index == 2) {
                main.TotalPiecesSize.forEach(Size => {
                    if (Size['Cat'] == "Gayer") {
                        NewArray.push(Size);
                    }
                });
                main.PiecesSize = NewArray;
            }
            main.TheUserSelection['SheetToPiecesSize'] = -1;
        },

        CalculateThePrice(SheetToPieces, CopiesInOnePiece) {
            let main = this;
            // Sheet --> Piece --> Copies [Paper Price]
            let PricePerTon = main.TheRawMaterial[main.TheUserSelection['RawMaterial']].Price;
            let PricePerGram = (PricePerTon / 1000000);
            let RequiredQty = main.TheUserSelection['Qty'];
            let SheetType = main.PiecesSize[main.TheUserSelection['SheetToPiecesSize']].Cat;
            let NumberOfPieces = Math.ceil(RequiredQty / CopiesInOnePiece);
            let NumberOfSheets = Math.ceil(NumberOfPieces / SheetToPieces);
            let NumberOfPicesInThousands = Math.ceil(NumberOfPieces / 1000);
            let SheetSize = parseFloat(1 * 0.7);
            if (SheetType != 'Standard') {
                SheetSize = parseFloat(0.66 * 0.88);
            }
            let SheetWeight = main.TheUserSelection['PaperWeight'];
            let PaperPrice = Math.ceil(PricePerGram * SheetSize * NumberOfSheets * SheetWeight);
            let ColorIndex = main.TheUserSelection['ColorIndex'] + 1;
            // CTP Price 
            let CTPID = main.PiecesSize[main.TheUserSelection['SheetToPiecesSize']].CTPID;
            let RawCTP = main.TheCTPMaterial[main.TheCTPMaterial.findIndex(function (item) { return item.id == CTPID })].Price;
            let FinalCTP = Math.ceil(RawCTP * ColorIndex);
            //Printing Price
            let TrajID = main.PiecesSize[main.TheUserSelection['SheetToPiecesSize']].TrajID;
            let RawTraj = main.TheTrajMaterial[main.TheTrajMaterial.findIndex(function (item) { return item.id == TrajID })].Price;
            let faceIndex = main.TheUserSelection['PrintingStyle'] + 1
            let FinalPrintingPrice = Math.ceil(NumberOfPicesInThousands * faceIndex * ColorIndex * RawTraj);
            let FinalPrice = PaperPrice + FinalCTP + FinalPrintingPrice;
            main.TheCalcResult['PaperPrice'] = PaperPrice;
            main.TheCalcResult['CtpPrice'] = FinalCTP;
            main.TheCalcResult['TrajPrice'] = FinalPrintingPrice;
            //After Printing
            let TheFinishPrice = [];
            main.TheFinish.forEach(finish => {
                if (finish['Selected'] == true) {
                    let Total = 0;
                    if (finish['Unit'] == 'Sheet') {
                        Total = NumberOfSheets * finish['Price'];
                    }
                    else if (finish['Unit'] == 'Thousand') {
                        Total = NumberOfPicesInThousands * finish['Price'];
                    }
                    let TheObject = {
                        name: finish['name'],
                        ArabicName: finish['ArabicName'],
                        price: Total,
                    }
                    TheFinishPrice.push(TheObject);
                }
            });
            main.TheFinishPrice = TheFinishPrice;
        },

        ExcuteApis() {
            let main = this;
            let Api_Url = main.$store.state['Api_Url'];
            axios.post(Api_Url, {
                api_name: "GetAllProducts",
            }).then(function (res) {
                let TheFinalProducts = [];
                let TheProducts = res.data['data'];
                TheProducts.forEach(product => {
                    let TheParent = "";
                    if (product['Parent_Product_Name'] != null) {
                        TheParent = product['Parent_Product_Name']['name'];
                    }
                    else {
                        TheParent = product['Parent_Product_Name'];
                    }
                    let Theobject = {
                        name: product['Product_Name'],
                        ArabicName: product['Product_Name_Arabic'],
                        Parent: TheParent
                    }
                    TheFinalProducts.push(Theobject);
                });
                main.TheTypes = TheFinalProducts.filter(GetParentSelect);
                main.TheProducts = TheFinalProducts.filter(GetChildrenSelect);
                function GetParentSelect(product) {
                    if (product['Parent'] === null) {
                        return product;
                    }
                }
                function GetChildrenSelect(product) {
                    if (product['Parent'] !== null) {
                        return product;
                    }
                }
                main.AllProducts = TheFinalProducts;
            });
            // Get The RawMaterials
            axios.post(Api_Url, {
                api_name: "GetRawMaterials",
            }).then(function (res) {
                let TheRawMaterials = res.data['data'];
                let TheFinalArray = [];
                TheRawMaterials.forEach(Material => {
                    let TheObjtct = {
                        name: Material['Name'],
                        ArabicName: Material['Material_Arabic_Name'],
                        Unit: Material['Material_Unit'],
                        Price: Material['Price_Per_Unit'],
                        Cat: Material['Category_Of_Raw_Material'],
                        id: Material['id'],
                        Selected: false,
                    };
                    TheFinalArray.push(TheObjtct);
                });
                function GetPaperMaterials(RawMaterial) {
                    if (RawMaterial['Cat'] == "Paper") {
                        return RawMaterial;
                    }
                }
                function GetCTPMaterials(RawMaterial) {
                    if (RawMaterial['Cat'] == "ctp") {
                        return RawMaterial;
                    }
                }
                function GetTrajMaterials(RawMaterial) {
                    if (RawMaterial['Cat'] == "traj") {
                        return RawMaterial;
                    }
                }
                function GetFinishOptions(RawMaterial) {
                    if (RawMaterial['Cat'] == "finish") {
                        return RawMaterial;
                    }
                }
                main.TheRawMaterial = TheFinalArray.filter(GetPaperMaterials);
                main.TheCTPMaterial = TheFinalArray.filter(GetCTPMaterials);
                main.TheTrajMaterial = TheFinalArray.filter(GetTrajMaterials);
                main.TheFinish = TheFinalArray.filter(GetFinishOptions);
            });
            // Get The GetPaperCutSize
            axios.post(Api_Url, {
                api_name: "GetPaperCutSize",
            }).then(function (res) {
                let TheResult = res.data['data'];
                // console.log(TheResult);
                let TheFinalArray = [];
                TheResult.forEach(Size => {
                    let TheObject = {
                        name: Size['Name'],
                        width: Size['Width'],
                        height: Size['Height'],
                        SheetToPieces: Size['Sheet_To_Pieces'],
                        CTPID: Size['CTP_Index']['id'],
                        TrajID: Size['The_Traj_Index']['id'],
                        Cat: Size['Sheet_Type'],
                    }
                    TheFinalArray.push(TheObject);
                    main.PiecesSize = TheFinalArray;
                    main.TotalPiecesSize = TheFinalArray;
                });
            });
            // Get Task Details 
            axios.post(Api_Url, {
                api_name: "GetTaskDetails",
                task_id: main.$store.state['TheTaskID'],
            }).then(function (res) {
                main.$store.state['TheTaskDetails'] = res.data['Requirement_Details'];
                main.TheUserSelection['Qty'] = res.data['Quantity'];
                main.$store.state['LoaderIndex'] = 0;
            });
        },

        UpdateData() {
            this.$store.state['TheUserSelection'] = this.TheUserSelection;
            this.$store.state['AllProducts'] = this.AllProducts;
            this.$store.state['TheProducts'] = this.TheProducts;
            this.$store.state['TheTypes'] = this.TheTypes;
            this.$store.state['TheSizes'] = this.TheSizes;
            this.$store.state['TotalPiecesSize'] = this.TotalPiecesSize;
            this.$store.state['PiecesSize'] = this.PiecesSize;
            this.$store.state['TheStyle'] = this.TheStyle;
            this.$store.state['TheFinish'] = this.TheFinish;
            this.$store.state['TheRawMaterial'] = this.TheRawMaterial;
            this.$store.state['TheCTPMaterial'] = this.TheCTPMaterial;
            this.$store.state['TheTrajMaterial'] = this.TheTrajMaterial;
            this.$store.state['TheCalcResult'] = this.TheCalcResult;
            this.$store.state['TheFinishPrice'] = this.TheFinishPrice;
        }
    },
    watch: {
        'TheUserSelection.RequestType'(newVal) {
            this.TheUserSelection['ProductSize'] = -1;
            this.TheUserSelection['ProductType'] = -1;
        },
        'TheUserSelection.ProductType'(newVal) {
            this.TheUserSelection['ProductSize'] = -1;
        },
        'TheUserSelection.ProductSize'(newVal) {
            if (newVal != this.TheSizes[this.TheSizes.findIndex(item => item.name === 'other')]) {
                this.TheSizes[this.TheSizes.findIndex(item => item.name === 'other')].height = 0;
                this.TheSizes[this.TheSizes.findIndex(item => item.name === 'other')].width = 0;
            }
        },
        UserLang(newVal) {
            this.$store.state['LoaderIndex'] = 1;
            setTimeout(() => {
                this.$store.state['LoaderIndex'] = 0;
            }, 2000);
            if (newVal == true) {
                this.$store.state['UserLang'] = 2;
            }
            else {
                this.$store.state['UserLang'] = 1;
            }
        }
    },
    computed: {
        TotalFishPrice() {
            let TheTotal = 0;
            this.TheFinishPrice.forEach(finish => {
                TheTotal = TheTotal + parseFloat(finish['price']);
            });
            return parseFloat(TheTotal);
        }
    },
}
</script>
  
<style lang="scss" scoped>
#FinalView {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;

    .PageHeader {
        background-color: #103e54;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: center;
        align-items: center;
        padding: 1rem 5%;
        justify-content: space-between;

        .AppInfo {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-content: center;
            justify-content: center;
            align-items: center;

            img {
                height: 3rem;
            }

            h1 {
                font-size: 1.2rem;
                color: white;
                padding-left: 1rem;
            }
        }

        .SelectLang {
            color: white;
        }

        .SideMenuBtn {
            color: white;
            cursor: pointer;
        }

    }

    .PageContent {
        @media screen and (max-width: 767px) {
            order: 2;
        }

        padding: 1rem 2%;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: flex-start;
        align-items: center;

        .SelectField {
            padding: 1rem;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-content: center;
            justify-content: center;
            align-items: center;

            label {
                font-size: 0.9rem;
                padding-bottom: 0.5rem;
            }

            select,
            input {
                font-size: 0.9rem;
                padding: 0.5rem;
            }

            select option {
                direction: ltr;
            }

        }

        .Finish {
            padding: 1rem 0;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            flex-wrap: wrap;
            align-content: flex-start;

            div {
                display: flex;
                margin: 0 1rem;
                flex-direction: row;
                flex-wrap: nowrap;
                padding: 0.5rem 0;
                align-items: center;
            }

            label {
                padding: 0 0.5rem;
            }

            input {
                scale: 1.4;
            }
        }

        .FlexCenter {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            align-content: center;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;

            input {
                padding: 0.25rem 0.5rem;
                font-size: 1.2rem;
            }
        }
    }

    .TaskDetails {
        @media screen and (max-width: 767px) {
            order: 1;
        }

        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 1rem;
        background-color: rgba(255, 255, 255, 0.4);
        font-size: 1.4rem;
        box-shadow: 1px 4px 6px rgb(128 128 128 / 68%);
        align-content: flex-start;

        h1 {
            font-size: 1.4rem;
            font-weight: 600;
            padding-bottom: 0.5rem !important;
        }

        pre {
            margin: 0;
        }

        p {
            font-size: 1.2rem;
        }
    }

    .PageFooter {
        @media screen and (max-width: 767px) {
            order: 3;
        }

        display: flex;
        padding: 1rem;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        align-items: center;

        button {
            font-size: 1.2rem;
            margin: 0 0.5rem;
        }
    }

    .FinishContainer {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: center;
        justify-content: space-between;
        align-items: center;
    }

    .PageCalc {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        align-items: center;
        padding: 1rem 5%;
    }

    .PopUpPage {
        position: fixed;
        display: flex;
        height: 100vh;
        background-color: rgba(0, 0, 0, 0.4);
        top: 0;
        right: 0;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        align-items: center;
        z-index: 100;

        .TheContent {
            background-color: white;
            height: 90vh;
            overflow-y: auto;
        }
    }
}
</style>