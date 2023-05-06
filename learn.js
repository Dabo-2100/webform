// Why We Use JavaScript
// 1- Modifictation of website content ==> jQuery Code
//    a ==> Using a Certain Rules
//    b ==> Trigger
//    c ==> Update
// 2- Cacluations ==> Functions and Logic (IF - For - While -...);

//### Data Types
// 1- Text ==> let UserName = 'Ahmed';
// 2- Number (int = [1,2,3] , float = [0.2,1.4,874.187]) ==> let UserAge = 25;
// 3- Boolean (true or false) ==> let IsSubsucribed = true;
// 4- Array ==> let MyCars = ["BMW","Fiat",""];
// 5- Object ==> let MyInfo = {"name" : "Abdel-fattah", "NativeLang" : "Arabic", }
// 6- Null ==> let YourMessageNumber = null;

//Where To Add JS Code
// 1- Separte file ==> index.js and link it to your HTML File Before Closing Tag of Body
//  ex
//  <script src="jQuery.js"></script>
//  <script src="index.js"></script>
//  </body>

// 2- Inside The HTML File
//<script>
//  alert('test');
//</script>

// js Logic
// is eqaul (a == b)
// not equal (a != b)
// greater than ( a > b )
// greater than or equal  (a >= b )
// And (a && b)
// OR (a || b)

// if(Condition){
// Block Of Code When the Condtion is true
// } else{
// The Code That Execute when condtion not happend
// }

// JS Function
// function TheFunctionName (Arrgument == input ){The Block Of Code}

// ## jQuery Modification
// Catch The Element and then make whatever you want
// $(Element).method();
// $("TheElement[.forClass,#forID,TheTagName]").function();
// The Most Common Functions to use in jQuery
// $("h1").text("The New Text Value");
// $("h1").html("<span>A HTML Code</span>");
// $("Table").append("<tr><td>Hello One</td></tr>" [Add Some Code Inside The Object])
// $("input").val() ==> To Get The Value Of The Input
// $("input").val('My Value') ==> To Set The Value Of Input
// $("Element").remove();
// $("p").css({"background-color": "yellow", "font-size": "2rem"}); To Change The CSS Properties

// Put a trigger
// $(Element).on(Event , Function);
// $("h1").on("click", function () {What Happend When The Event Done});

// Next Section
// The Array and The Object
// The For Loop
// .attr()
// Make a full Login Page

function GetFinalValue(Qty, Price, Discount, UserType) {
  let DiscountPercentage = Discount / 100; // عشان نجيب النسبة المئوية للخصم
  let TotalPrice = Qty * Price; // القيمة الاجمالية للفاتورة
  let DiscountValue = TotalPrice * DiscountPercentage; // قيمة الخصم للفاتورة
  let FinalPrice = TotalPrice - DiscountValue; // الاجمالي صافي بعد طرح قيمة الخصم وبدون الضريبة
  let FinalWithVat = FinalPrice + FinalPrice * (14 / 100); // قيمة الاجمالي بعد طرح الخصم واضافة الضريبة
  return FinalWithVat;
}
alert(GetFinalValue(10, 200, 12, 1));

function GetThePantsColor(ShirtColor) {
    
}
