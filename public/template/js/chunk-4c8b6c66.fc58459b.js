(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-4c8b6c66"],{"467b":function(a,t,r){"use strict";r.r(t);var s,e,i,l,c,o,n,d,b,g,h,u,C=function(){var a=this,t=a.$createElement,r=a._self._c||t;return r("section",{staticClass:"chartjs"},[a._m(0),r("div",{staticClass:"row"},[r("div",{staticClass:"col-lg-6 grid-margin stretch-card"},[r("div",{staticClass:"card"},[r("div",{staticClass:"card-body"},[r("h4",{staticClass:"card-title"},[a._v("Line chart")]),r("line-chart",{attrs:{height:250}})],1)])]),r("div",{staticClass:"col-lg-6 grid-margin stretch-card"},[r("div",{staticClass:"card"},[r("div",{staticClass:"card-body"},[r("h4",{staticClass:"card-title"},[a._v("Bar chart")]),r("bar-chart",{attrs:{height:250}})],1)])])]),r("div",{staticClass:"row"},[r("div",{staticClass:"col-lg-6 grid-margin stretch-card"},[r("div",{staticClass:"card"},[r("div",{staticClass:"card-body"},[r("h4",{staticClass:"card-title"},[a._v("Area chart")]),r("area-chart",{attrs:{height:250}})],1)])]),r("div",{staticClass:"col-lg-6 grid-margin stretch-card"},[r("div",{staticClass:"card"},[r("div",{staticClass:"card-body"},[r("h4",{staticClass:"card-title"},[a._v("Doughnut chart")]),r("doughnut-chart",{attrs:{height:200}})],1)])])]),r("div",{staticClass:"row"},[r("div",{staticClass:"col-lg-6 grid-margin stretch-card"},[r("div",{staticClass:"card"},[r("div",{staticClass:"card-body"},[r("h4",{staticClass:"card-title"},[a._v("Pie chart")]),r("pie-chart",{attrs:{height:200}})],1)])]),r("div",{staticClass:"col-lg-6 grid-margin stretch-card"},[r("div",{staticClass:"card"},[r("div",{staticClass:"card-body"},[r("h4",{staticClass:"card-title"},[a._v("Scatter chart")]),r("scatter-chart",{attrs:{height:200}})],1)])])])])},p=[function(){var a=this,t=a.$createElement,r=a._self._c||t;return r("div",{staticClass:"page-header"},[r("h3",{staticClass:"page-title"},[a._v(" ChartJS ")]),r("nav",{attrs:{"aria-label":"breadcrumb"}},[r("ol",{staticClass:"breadcrumb"},[r("li",{staticClass:"breadcrumb-item"},[r("a",{attrs:{href:"javascript:void(0);"}},[a._v("Charts")])]),r("li",{staticClass:"breadcrumb-item active",attrs:{"aria-current":"page"}},[a._v("Chartjs")])])])])}],y=r("1fca"),v={extends:y["c"],data:function(){return{datacollection:{labels:["Day 1","Day 2","Day 3","Day 4","Day 5","Day 6"],datasets:[{label:"# of Votes",data:[10,19,3,5,2,3],borderColor:["rgba(255,99,132,1)"],borderWidth:1,fill:!1}]},options:{scales:{yAxes:[{ticks:{beginAtZero:!0},gridLines:{display:!0}}],xAxes:[{ticks:{beginAtZero:!0},gridLines:{display:!0}}]},legend:{display:!0},elements:{point:{radius:0}}}}},mounted:function(){this.renderChart(this.datacollection,this.options)}},m=v,x=r("2877"),f=Object(x["a"])(m,s,e,!1,null,null,null),D=f.exports,k={extends:y["a"],data:function(){return{datacollection:{labels:["Day 1","Day 2","Day 3","Day 4","Day 5","Day 6"],datasets:[{label:"# of Votes",data:[10,19,3,5,2,3],backgroundColor:["rgba(255, 99, 132, 0.2)","rgba(54, 162, 235, 0.2)","rgba(255, 206, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(153, 102, 255, 0.2)","rgba(255, 159, 64, 0.2)"],borderColor:["rgba(255,99,132,1)","rgba(54, 162, 235, 1)","rgba(255, 206, 86, 1)","rgba(75, 192, 192, 1)","rgba(153, 102, 255, 1)","rgba(255, 159, 64, 1)"],borderWidth:1}]},options:{scales:{yAxes:[{ticks:{beginAtZero:!0},gridLines:{display:!0}}],xAxes:[{ticks:{beginAtZero:!0},gridLines:{display:!0}}]},legend:{display:!1},elements:{point:{radius:0}}}}},mounted:function(){this.renderChart(this.datacollection,this.options)}},A=k,_=(r("ef94"),Object(x["a"])(A,i,l,!1,null,"88a47d58",null)),j=_.exports,w={name:"area-chart",extends:y["c"],data:function(){return{datacollection:{labels:["Day 1","Day 2","Day 3","Day 4","Day 5","Day 6"],datasets:[{label:"# of Votes",data:[10,19,3,5,2,3],backgroundColor:["rgba(255, 99, 132, 0.2)"],borderColor:["rgba(255,99,132,1)"],borderWidth:1}]},options:{scales:{yAxes:[{ticks:{beginAtZero:!0},gridLines:{display:!0}}],xAxes:[{ticks:{beginAtZero:!0},gridLines:{display:!0}}]},legend:{display:!0},elements:{point:{radius:0}}}}},mounted:function(){this.renderChart(this.datacollection,this.options)}},L=w,O=Object(x["a"])(L,c,o,!1,null,null,null),Z=O.exports,S={extends:y["b"],data:function(){return{datacollection:{labels:["Pink","Blue","Yellow"],datasets:[{data:[30,40,30],backgroundColor:["rgba(255, 99, 132, 0.5)","rgba(54, 162, 235, 0.5)","rgba(255, 206, 86, 0.5)","rgba(75, 192, 192, 0.5)","rgba(153, 102, 255, 0.5)","rgba(255, 159, 64, 0.5)"],borderColor:["rgba(255,99,132,1)","rgba(54, 162, 235, 1)","rgba(255, 206, 86, 1)","rgba(75, 192, 192, 1)","rgba(153, 102, 255, 1)","rgba(255, 159, 64, 1)"]}]},options:{responsive:!0,animation:{animateScale:!0,animateRotate:!0}}}},mounted:function(){this.renderChart(this.datacollection,this.options)}},W=S,B=Object(x["a"])(W,n,d,!1,null,null,null),J=B.exports,P={extends:y["d"],data:function(){return{datacollection:{labels:["Pink","Blue","Yellow"],datasets:[{data:[30,40,30],backgroundColor:["rgba(255, 99, 132, 0.5)","rgba(54, 162, 235, 0.5)","rgba(255, 206, 86, 0.5)","rgba(75, 192, 192, 0.5)","rgba(153, 102, 255, 0.5)","rgba(255, 159, 64, 0.5)"],borderColor:["rgba(255,99,132,1)","rgba(54, 162, 235, 1)","rgba(255, 206, 86, 1)","rgba(75, 192, 192, 1)","rgba(153, 102, 255, 1)","rgba(255, 159, 64, 1)"]}]},options:{responsive:!0,animation:{animateScale:!0,animateRotate:!0}}}},mounted:function(){this.renderChart(this.datacollection,this.options)}},V=P,E=Object(x["a"])(V,b,g,!1,null,null,null),R=E.exports,Y={extends:y["c"],data:function(){return{datacollection:{labels:["0","1","2","3","4","5","6","7","8","9"],datasets:[{label:"First Dataset",data:[{x:-10,y:0},{x:0,y:3},{x:-25,y:5},{x:40,y:5}],backgroundColor:["rgba(255, 99, 132, 0.2)"],borderColor:["rgba(255,99,132,1)"],borderWidth:1},{label:"Second Dataset",data:[{x:10,y:5},{x:20,y:-30},{x:-25,y:15},{x:-10,y:5}],backgroundColor:["rgba(54, 162, 235, 0.2)"],borderColor:["rgba(54, 162, 235, 1)"],borderWidth:1}]},options:{scales:{xAxes:[{type:"linear",position:"bottom"}]}}}},mounted:function(){this.renderChart(this.datacollection,this.options)}},$=Y,F=Object(x["a"])($,h,u,!1,null,null,null),q=F.exports,z={name:"chartjs",components:{lineChart:D,barChart:j,areaChart:Z,doughnutChart:J,pieChart:R,scatterChart:q}},G=z,H=Object(x["a"])(G,C,p,!1,null,null,null);t["default"]=H.exports},b1b8:function(a,t,r){},ef94:function(a,t,r){"use strict";r("b1b8")}}]);
//# sourceMappingURL=chunk-4c8b6c66.fc58459b.js.map