<template>
    <div class="col-12" id="MaterialView">
        <h1 class="col-12 TabHeader">اسعار الخامات</h1>
        <table class="col-12 table table-hover table-bordered">
            <thead>
                <tr>
                    <th class="col-1">-</th>
                    <th class="col-5">الإسم</th>
                    <th class="col-3">السعر</th>
                    <th class="col-2">الوحدة</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="Material, index in this.RawMaterial" :key="Material" :id="Material['id']">
                    <td>{{ index + 1 }}</td>
                    <td>{{ Material['ArabicName'] }}</td>
                    <td><input v-model="Material['Price']" @change="this.ChangePrice(index)"></td>
                    <td>{{ Material['Unit'] == 'TON' ? 'الطن' : 'للزنكة' }}</td>
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
            main.RawMaterial = TheFinalArray.filter(GetRawMaterials);
            main.$store.state['LoaderIndex'] = 0;
        });
    },
    methods: {
        ChangePrice(index) {
            let main = this;
            this.$swal.fire({
                title: 'هل تريد تعديل سعر المادة الخام الجديدة الي :' + main.RawMaterial[index]['Price'] + '  جنيهاً ',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم لنقم بذلك',
                cancelButtonText: 'ليس الأن'
            }).then((result) => {
                if (result.isConfirmed) {
                    main.$store.state['LoaderIndex'] = 1;
                    axios.post(main.Api_Url, {
                        api_name: "UpdateRawMaterialPrice",
                        raw_material_price: main.RawMaterial[index]['Price'],
                        raw_material_id: main.RawMaterial[index]['id'],
                    }).then(function (res) {
                        // console.log()
                        // console.log(res.data);
                        main.$store.state['LoaderIndex'] = 0;
                        main.$swal.fire(
                            'تم تعديل سعر الخامة بنجاح',
                        )
                    });
                }
                else {
                    main.$store.state['LoaderIndex'] = 0;
                }
            })
        }
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
        font-size: 1.3rem;

        th {
            font-size: 1.2rem;
        }

        td {
            input {
                font-size: 1.2rem;
                padding: 0.25rem;
                width: 7rem;
            }
        }
    }
}
</style>
  
  
  