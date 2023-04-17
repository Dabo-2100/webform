<template>
    <div class="col-12" id="MaterialView">
        <h1 class="col-12 TabHeader">اسعار الخامات</h1>
        <table class="col-12 table table-hover table-bordered">
            <thead>
                <tr>
                    <th>-</th>
                    <th>اسم المادة</th>
                    <th>السعر</th>
                    <th>وحدة القياس</th>
                    <!-- <th>نوع المادة</th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="Material, index in this.RawMaterial" :key="Material" :id="Material['id']">
                    <td>{{ index + 1 }}</td>
                    <td>{{ Material['ArabicName'] }}</td>
                    <td>{{ Material['Price'] }}</td>
                    <td>{{ Material['Unit'] }}</td>
                    <!-- <td>{{ Material['Cat'] }}</td> -->
                </tr>
            </tbody>
        </table>
    </div>
</template>
  
<script>
// @ is an alias to /src
import axios from 'axios';
export default {
    name: 'MaterialView',
    components: {},
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
            RawMaterial: [],
        };
    },
    created() {
        let main = this;
        this.$store.state['LoaderIndex'] = 1;
        axios.post(this.Api_Url, {
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
            function GetRawMaterials(RawMaterial) {
                if (RawMaterial['Cat'] != "finish") {
                    return RawMaterial;
                }
            }
            console.log(TheFinalArray.filter(GetRawMaterials));
            main.RawMaterial = TheFinalArray.filter(GetRawMaterials);
            main.$store.state['LoaderIndex'] = 0;
        });
    },
    methods: {

    },
    watch: {

    },
    computed: {

    },
}
</script>
  
<style lang="scss" scoped>
#MaterialView {
    display: flex;
    direction: rtl;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    align-items: center;

    .TabHeader {
        font-size: 1.4rem;
        padding: 1rem;
    }

    table {
        text-align: center;
        vertical-align: middle;
    }
}
</style>
  
  
  