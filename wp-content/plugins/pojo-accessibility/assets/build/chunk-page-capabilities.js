"use strict";(globalThis.webpackChunkpojo_accessibility=globalThis.webpackChunkpojo_accessibility||[]).push([[3313],{24200(e,i,t){t.d(i,{JX:()=>d,HQ:()=>ze,DG:()=>L,hP:()=>v,Fy:()=>I,Vo:()=>ae,En:()=>we,mo:()=>$e,Bx:()=>ee,u5:()=>ye,P6:()=>q});var o=t(78048),s=t(5322),n=t(85848),a=t(95231),r=t(46097),l=t(27723),c=t(10790);const d=e=>(0,c.jsxs)(p,{...e,children:[(0,c.jsxs)(o.A,{marginBottom:2,children:[(0,c.jsx)(n.A,{variant:"subtitle1",children:(0,l.__)("Style","pojo-accessibility")}),(0,c.jsx)(n.A,{variant:"body2",children:(0,l.__)("Customize your widget's color, icon, and size to match your brand.","pojo-accessibility")})]}),(0,c.jsxs)(h,{children:[(0,c.jsxs)(o.A,{display:"flex",flexDirection:"column",gap:5,children:[(0,c.jsx)(r.Ex,{}),(0,c.jsx)(r.lK,{})]}),(0,c.jsx)(o.A,{children:(0,c.jsx)(r.sk,{})})]}),(0,c.jsx)(h,{children:(0,c.jsx)(r.ev,{})})]}),p=(0,a.I)(o.A)`
	padding: ${({theme:e})=>e.spacing(2)};
	border: 1px solid ${({theme:e})=>e.palette.divider};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	margin-left: auto;
	margin-right: auto;
	max-width: 1200px;
`,h=(0,a.I)(s.A)`
	display: grid;
	gap: ${({theme:e})=>e.spacing(5)};
	padding: ${({theme:e})=>e.spacing(2)};
	grid-template-columns: repeat(2, 1fr);

	${({theme:e})=>e.breakpoints.down("lg")} {
		grid-template-columns: repeat(1, 1fr);
	}
`;var x=t(73755),u=t(20709),g=t(93839),m=t(60688),b=t(33525),j=t(77784),y=t(86087);const f="one",v=e=>{const[i,t]=(0,y.useState)(f),{getTabProps:s}=(0,j.A)(i),a=e=>()=>{t(e)};return(0,c.jsxs)(_,{...e,children:[(0,c.jsxs)(o.A,{marginBottom:2,children:[(0,c.jsx)(n.A,{variant:"subtitle1",children:(0,l.__)("Position","pojo-accessibility")}),(0,c.jsx)(n.A,{variant:"body2",children:(0,l.__)("Decide where you want your accessibility button to appear across every page of your site so visitors can easily find it.","pojo-accessibility")})]}),(0,c.jsx)(o.A,{padding:2,children:(0,c.jsxs)(b.A,{value:i,sx:{borderBottom:"none",height:"65px"},indicatorColor:"secondary",textColor:"secondary",children:[(0,c.jsx)(g.A,{...s(f),label:(0,l.__)("Desktop","pojo-accessibility"),icon:(0,c.jsx)(x.A,{}),iconPosition:"start",onClick:a(f)}),(0,c.jsx)(g.A,{...s("two"),label:(0,l.__)("Mobile","pojo-accessibility"),icon:(0,c.jsx)(u.A,{}),iconPosition:"start",onClick:a("two")})]})}),i===f?(0,c.jsx)(m.A,{children:(0,c.jsx)(I,{})}):(0,c.jsx)(m.A,{children:(0,c.jsx)(ae,{})})]})},_=(0,a.I)(s.A)`
	padding: ${({theme:e})=>e.spacing(2)};
	border: 1px solid ${({theme:e})=>e.palette.divider};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	margin-left: auto;
	margin-right: auto;
	max-width: 1200px;
`;var A=t(93248),C=t(98053),w=t(9626),k=t(70276);const I=()=>{const{iconPosition:e,updateIconPosition:i}=(0,w.Aw)(),t=(0,c.jsx)(n.A,{variant:"subtitle2",marginRight:2,color:"text.primary",children:(0,l.__)("Hide on desktop","pojo-accessibility")}),s=(0,c.jsx)(n.A,{variant:"subtitle2",color:"text.primary",marginRight:2,children:(0,l.__)("Exact position","pojo-accessibility")});return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(A.A,{label:t,labelPlacement:"start",control:(0,c.jsx)(C.A,{color:"info",size:"small"}),sx:{marginLeft:2,marginBottom:3},onChange:()=>{return i(t="desktop","hidden",!e[t].hidden),void k.K.sendEvent(k.m.toggleClicked,{state:e[t].hidden?"on":"off",type:"Hide on desktop",device:t});var t},checked:e.desktop.hidden}),!e.desktop.hidden&&(0,c.jsxs)(r.cy,{children:[(0,c.jsx)(r.or,{mode:"desktop"}),(0,c.jsxs)(o.A,{children:[(0,c.jsx)(A.A,{label:s,labelPlacement:"start",control:(0,c.jsx)(C.A,{color:"info",size:"small"}),sx:{marginLeft:0},onChange:()=>{return i(t="desktop","enableExactPosition",!e[t].enableExactPosition),void k.K.sendEvent(k.m.toggleClicked,{state:e[t].enableExactPosition?"on":"off",type:"Exact position",device:t});var t},checked:e.desktop?.enableExactPosition}),(0,c.jsx)(n.A,{id:"ea11y-desktop-position-settings",variant:"body2",sx:{marginTop:2,marginBottom:1},children:(0,l.__)("Exact positioning, 5 – 500 px are permitted values:","pojo-accessibility")}),(0,c.jsx)(r.Zh,{type:"horizontal",mode:"desktop",disabled:!e.desktop?.enableExactPosition}),(0,c.jsx)(r.Zh,{type:"vertical",mode:"desktop",disabled:!e.desktop?.enableExactPosition})]})]})]})};var D=t(95408),S=t(40730),E=t(86269),M=t(2660),P=t(29350),$=t(95726),R=t(57936),z=t(43307),T=t(43331);const B={"content-adjustments":{title:(0,l.__)("Content Adjustments","pojo-accessibility"),options:{"bigger-text":T.xT["bigger-text"],"bigger-line-height":T.xT["bigger-line-height"],"text-align":T.xT["text-align"],"readable-font":T.xT["readable-font"]}},"orientation-adjustments":{title:(0,l.__)("Orientation Adjustments","pojo-accessibility"),options:{"page-structure":T.xT["page-structure"],"screen-reader":T.xT["screen-reader"],sitemap:T.xT.sitemap,"reading-mask":T.xT["reading-mask"],"language-selector":T.xT["language-selector"],"hide-images":T.xT["hide-images"],"pause-animations":T.xT["pause-animations"],"highlight-links":T.xT["highlight-links"],"focus-outline":T.xT["focus-outline"]}},"color-adjustments":{title:(0,l.__)("Color Adjustments","pojo-accessibility"),options:{grayscale:T.xT.grayscale,contrast:T.xT.contrast}}},H=(0,a.I)(E.A)`
	height: 55vh;
	overflow: auto;
	margin-bottom: ${({theme:e})=>e.spacing(2)};
	padding: 0 ${({theme:e})=>e.spacing(2)};
`,L=()=>{const{widgetMenuSettings:e,hideMinimumOptionAlert:i,setHideMinimumOptionAlert:t}=(0,w.t0)(),[s,a]=(0,y.useState)(!1),{save:d}=(0,w.rX)();(0,y.useEffect)(()=>{p(e)?(a(!1),d({a11y_hide_minimum_active_options_alert:!1}).then(()=>{t(!1)})):a(!0)},[e]);const p=e=>{const i=Object.keys(e)?.filter(i=>e[i].enabled);return i.length>2},h=Object.entries(B).length;return(0,c.jsxs)(o.A,{display:"flex",flexDirection:"column",gap:2,children:[(0,c.jsxs)(S.A,{variant:"outlined",children:[(0,c.jsx)(M.A,{title:(0,l.__)("Feature Menu","pojo-accessibility"),subheader:(0,c.jsx)(n.A,{variant:"body2",children:(0,l.__)("Choose which accessibility features and capabilities you want to include.","pojo-accessibility")})}),s&&!i&&(0,c.jsx)(D.Ay,{severity:"info",sx:{m:2},onClose:()=>{d({a11y_hide_minimum_active_options_alert:!0}).then(()=>{t(!0)})},children:(0,l.__)("At least two option must remain active","pojo-accessibility")}),(0,c.jsx)(H,{children:(0,c.jsx)($.A,{as:"div",children:Object.entries(B).map(([e,i],t)=>(0,c.jsxs)(o.A,{children:[(0,c.jsx)(R.A,{as:"div",disableGutters:!0,children:(0,c.jsx)(z.A,{sx:{textAlign:"start"},children:(0,c.jsx)(n.A,{variant:"subtitle2",children:i.title})})}),i.options&&Object.entries(i.options).map(([i,t])=>(0,c.jsx)(r.uM,{childKey:i,childValue:t,parentKey:e,disableOptions:s},i)),t+1<h&&(0,c.jsx)(P.A,{sx:{my:2}})]},e))})})]}),(0,c.jsx)(ze,{})]})};var O=t(71676),V=t(5556),F=t.n(V),Z=t(43516);const W=(0,a.I)(O.A)`
	justify-content: center;
	display: flex;
`,N=(0,y.memo)(()=>(0,c.jsxs)(Z.Ay,{speed:T.Vb.SPEED,foregroundColor:T.Vb.FOREGROUND_COLOR,height:497,width:393,children:[(0,c.jsx)("rect",{x:"19",y:"18",width:"99.6184",height:"16.2418",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"292",y:"10",width:"32.8412",height:"32.4837",rx:"16.2418",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"244",y:"10",width:"32.8412",height:"32.4837",rx:"16.2418",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"340",y:"10",width:"32.8412",height:"32.4837",rx:"16.2418",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"17",y:"476",width:"134",height:"14",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"324",y:"476",width:"31",height:"14",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"360",y:"476",width:"14.2312",height:"14.0763",rx:"7.03813",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"17",y:"76",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"17",y:"164",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"141",y:"164",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"265",y:"164",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"17",y:"252",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"141",y:"252",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"265",y:"252",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"17",y:"340",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"141",y:"340",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"264",y:"340",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"17",y:"428",width:"109",height:"42",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"141",y:"76",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"265",y:"76",width:"109.471",height:"73.6296",rx:"4",fill:"#D9D9D9"}),(0,c.jsx)("line",{y1:"56.5",x2:"393",y2:"56.5",stroke:"#D9D9D9"}),(0,c.jsx)("line",{y1:"469.5",x2:"393",y2:"469.5",stroke:"#D9D9D9"})]})),G=(0,y.memo)(({children:e})=>(0,c.jsxs)(W,{children:[(0,c.jsx)(N,{}),e]}));G.propTypes={children:F().node};const K=G,U=(0,a.I)(E.A)`
	margin-right: auto;
	margin-left: auto;
	margin-top: ${({theme:e})=>e.spacing(4)};
	padding: 0 24px;

	overflow: auto;

	& #ea11y-root {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;

		transform: scale(70%);
	}

	& #ea11y-root #ea11y-widget-container {
		position: initial;
		transform: translateY(0);
	}

	@media screen and (max-height: 850px) {
		& #ea11y-root {
			top: -25px;
			transform: scale(60%);
		}
	}
`,q=()=>{const[e,i]=(0,y.useState)(!1);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsxs)(S.A,{variant:"outlined",children:[(0,c.jsx)(M.A,{title:(0,l.__)("Preview","pojo-accessibility"),subheader:(0,c.jsx)(n.A,{variant:"body2",children:(0,l.__)("This is what the widget will look like to your site viewers.","pojo-accessibility")}),sx:{paddingBottom:0}}),(0,c.jsx)(U,{id:T.oG,children:!e&&(0,c.jsx)(K,{})})]}),(0,c.jsx)(r.Ov,{onLoad:()=>{i(!0),document.getElementById(T.oG)&&window?.ea11yWidget?.widget?.open()}})]})};if(9884==t.j)var Q=t(42473);var Y=t(39186),X=t(73916),J=t(42679);const ee=9884==t.j?()=>{const{openSidebar:e,setOpenSidebar:i}=(0,w.t0)(),{isElementorOne:t}=(0,J.F)();return(0,c.jsxs)(te,{variant:"permanent",open:e,role:"navigation","aria-label":(0,l.__)("Sidebar","pojo-accessibility"),children:[(0,c.jsx)(ie,{onClick:()=>i(!e),size:"small","aria-label":(0,l.__)("Toggle sidebar","pojo-accessibility"),children:(0,c.jsx)(Q.A,{"aria-hidden":!0,fontSize:"tiny",sx:{rotate:e?"0":"180deg"}})}),(0,c.jsxs)(oe,{children:[(0,c.jsx)(r.Nt,{}),(0,c.jsx)(P.A,{})]}),(0,c.jsx)(se,{children:(0,c.jsx)(r.wZ,{})}),!t&&(0,c.jsxs)(ne,{children:[(0,c.jsx)(P.A,{}),(0,c.jsx)(we,{})]})]})}:null,ie=(0,a.I)(X.A)`
	position: absolute;
	inset-inline-end: -15px;
	inset-block-start: 58px;
	z-index: 999999;

	border: 1px solid ${({theme:e})=>e.palette.divider};
	background: ${({theme:e})=>e.palette.background.paper};

	:hover,
	:focus-visible {
		background: #f3f3f4;
	}
`,te=(0,a.I)(Y.A,{shouldForwardProp:e=>"open"!==e})`
	width: auto;
	& .MuiDrawer-paper {
		position: relative;
		width: ${({open:e})=>e?"240px":"72px"};
		height: 100%;
		justify-content: space-between;
		padding-block-start: 0;
		overflow: visible;
		transition: all 0.3s;
	}
`,oe=(0,a.I)("div")`
	flex-shrink: 0;
	padding: ${({theme:e})=>e.spacing(2,2,0)};
	display: flex;
	flex-direction: column;
	gap: ${({theme:e})=>e.spacing(2)};
`,se=(0,a.I)("div")`
	flex: 1;
	padding: ${({theme:e})=>e.spacing(2)};
	overflow-y: auto;
`,ne=(0,a.I)("div")`
	flex-shrink: 0;
`,ae=()=>{const{iconPosition:e,updateIconPosition:i}=(0,w.Aw)(),t=(0,c.jsx)(n.A,{variant:"subtitle2",color:"text.primary",marginRight:2,children:(0,l.__)("Hide on mobile","pojo-accessibility")}),s=(0,c.jsx)(n.A,{variant:"subtitle2",color:"text.primary",marginRight:2,children:(0,l.__)("Exact position","pojo-accessibility")});return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(A.A,{label:t,labelPlacement:"start",control:(0,c.jsx)(C.A,{color:"info",size:"small"}),sx:{marginLeft:2,marginBottom:3},onChange:()=>{return i(t="mobile","hidden",!e[t].hidden),void k.K.sendEvent(k.m.toggleClicked,{state:e[t].hidden?"on":"off",type:"Hide on mobile",device:t});var t},checked:e.mobile.hidden}),!e.mobile.hidden&&(0,c.jsxs)(r.cy,{children:[(0,c.jsx)(r.or,{mode:"mobile"}),(0,c.jsxs)(o.A,{children:[(0,c.jsx)(A.A,{label:s,labelPlacement:"start",control:(0,c.jsx)(C.A,{color:"info",size:"small"}),sx:{marginLeft:0},onChange:()=>{return i(t="mobile","enableExactPosition",!e[t].enableExactPosition),void k.K.sendEvent(k.m.toggleClicked,{state:e[t].enableExactPosition?"on":"off",type:"Exact position",device:t});var t},checked:e.mobile?.enableExactPosition}),(0,c.jsx)(n.A,{id:"ea11y-mobile-position-settings",variant:"body2",sx:{marginTop:2,marginBottom:1},children:(0,l.__)("Exact positioning, 5 – 500 px are permitted values:","pojo-accessibility")}),(0,c.jsx)(r.Zh,{type:"horizontal",mode:"mobile",disabled:!e.mobile?.enableExactPosition}),(0,c.jsx)(r.Zh,{type:"vertical",mode:"mobile",disabled:!e.mobile?.enableExactPosition})]})]})]})};var re=t(86853),le=t(86752),ce=t(63364),de=t(99028),pe=t(96989),he=t(208);const xe=(0,a.I)(o.A)`
	padding: 0;

	justify-content: center;
	display: flex;
`,ue=(0,y.memo)(()=>(0,c.jsxs)(Z.Ay,{speed:T.Vb.SPEED,foregroundColor:T.Vb.FOREGROUND_COLOR,height:249,width:537,children:[(0,c.jsx)("rect",{x:"25",y:"221",width:"191",height:"15",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"444",y:"222",width:"42",height:"15",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"492",y:"219",width:"20",height:"20",rx:"10",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"25",y:"26",width:"152",height:"110",rx:"10",fill:"#D9D9D9"}),(0,c.jsx)("path",{d:"M25 0H177V0C177 5.52285 172.523 10 167 10H35C29.4772 10 25 5.52285 25 0V0Z",fill:"#D9D9D9"}),(0,c.jsx)("path",{d:"M193 0H345V0C345 5.52285 340.523 10 335 10H203C197.477 10 193 5.52285 193 0V0Z",fill:"#D9D9D9"}),(0,c.jsx)("path",{d:"M362 0H514V0C514 5.52285 509.523 10 504 10H372C366.477 10 362 5.52285 362 0V0Z",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"193",y:"26",width:"152",height:"110",rx:"10",fill:"#D9D9D9"}),(0,c.jsx)("rect",{x:"362",y:"26",width:"152",height:"110",rx:"10",fill:"#D9D9D9"}),(0,c.jsx)("path",{d:"M25 162C25 156.477 29.4772 152 35 152H167C172.523 152 177 156.477 177 162V209H25V162Z",fill:"#D9D9D9"}),(0,c.jsx)("line",{y1:"208.5",x2:"536.903",y2:"208.5",stroke:"#D9D9D9"})]})),ge=(0,y.memo)(({children:e})=>(0,c.jsxs)(xe,{children:[(0,c.jsx)(ue,{}),e]}));ge.propTypes={children:F().node};const me=ge,be=(0,a.I)(o.A)`
	margin-top: ${({theme:e})=>e.spacing(2)};

	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	border: 1px solid ${({theme:e})=>e.palette.divider};

	height: 250px;
	position: relative;
	padding: 25px;
	overflow: hidden;

	& .ea11y-widget-container {
		transform: translateY(0);
	}

	& .ea11y-widget-container--preview {
		position: absolute;
	}

	& .ea11y-widget-content::after {
		content: '';

		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		bottom: 40px;

		background-color: #fcfdff;
		opacity: 0.6;
	}

	@media screen and (min-width: 480px) {
		& .ea11y-widget-container--preview {
			bottom: 0;
			right: 0;
		}
	}
`,je=(0,a.I)(C.A)`
	input {
		height: 56px !important;
	}
`,ye=()=>{const[e,i]=(0,y.useState)(!0),[t,s]=(0,y.useState)(!1),{accessibilityStatementData:a,setAccessibilityStatementData:d}=(0,w.t0)(),{save:p}=(0,w.rX)(),{success:h,error:x}=(0,he.m)(),[u,g]=(0,y.useState)(!1);return(0,y.useEffect)(()=>{window?.ea11yWidget&&(a.hideLink?window.ea11yWidget.accessibilityStatementURL=null:window.ea11yWidget.accessibilityStatementURL=a?.link),window?.ea11yWidget?.widget?.updateState()},[a?.hideLink,a?.link]),(0,y.useEffect)(()=>{i(!1),a?.link?s(!0):(i(!0),s(!1))},[a]),(0,y.useEffect)(()=>{i(!0)},[]),(0,c.jsxs)(S.A,{elevation:0,variant:"outlined",sx:{marginTop:1,width:"100%"},children:[(0,c.jsx)(M.A,{title:(0,l.__)("Statement link","pojo-accessibility"),subheader:(0,l.__)("Link your accessibility statement page to your accessibility widget.","pojo-accessibility"),sx:{borderBottom:"1px solid",borderBottomColor:"divider"}}),(0,c.jsx)(E.A,{children:(0,c.jsxs)(o.A,{display:"grid",gridTemplateColumns:"repeat(2, 1fr)",gap:5,children:[(0,c.jsxs)(o.A,{display:"flex",flexDirection:"column",children:[(0,c.jsxs)(le.A,{fullWidth:!0,sx:{marginBottom:2},children:[(0,c.jsx)(de.A,{sx:{marginBottom:1},children:(0,c.jsx)(n.A,{variant:"subtitle2",color:"text.primary",children:(0,l.__)("Choose which page to link","pojo-accessibility")})}),(0,c.jsxs)(o.A,{display:"flex",flexDirection:"row",alignItems:"center",width:"100%",children:[(0,c.jsx)(r.O7,{}),a?.link&&(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(r.Lp,{}),(0,c.jsx)(r.Mj,{content:a?.link})]})]}),!t&&(0,c.jsx)(ce.A,{children:(0,l.__)("Please select a page","pojo-accessibility")})]}),(0,c.jsxs)(le.A,{fullWidth:!0,children:[(0,c.jsx)(de.A,{sx:{marginBottom:2,marginTop:2},children:(0,c.jsx)(n.A,{variant:"subtitle2",color:"text.primary",children:(0,l.__)("Want to hide the link?","pojo-accessibility")})}),(0,c.jsx)(A.A,{label:(0,l.__)("Hide link","pojo-accessibility"),labelPlacement:"start",control:(0,c.jsx)(je,{color:"info",size:"small",sx:{marginLeft:3}}),sx:{marginBottom:3,alignSelf:"start",ml:0},onChange:()=>{d({...a,hideLink:!a.hideLink}),k.K.sendEvent(k.m.toggleClicked,{state:a.hideLink?"on":"off",type:"Hide link"})},checked:a?.hideLink})]})]}),(0,c.jsxs)(o.A,{children:[(0,c.jsx)(n.A,{variant:"subtitle2",color:"text.primary",children:(0,l.__)("Preview link in widget","pojo-accessibility")}),(0,c.jsxs)(be,{id:"ea11y-widget-preview--container",sx:{padding:u?"initial":0},children:[!u&&(0,c.jsx)(me,{}),(0,c.jsx)(r.Ov,{onLoad:()=>{g(!0),document.getElementById(T.oG)&&window?.ea11yWidget?.widget?.open()}})]})]})]})}),(0,c.jsx)(re.A,{children:(0,c.jsx)(pe.A,{color:"primary",variant:"contained",onClick:async()=>{try{await p({ea11y_accessibility_statement_data:a}),await h("Changes saved","pojo-accessibility"),i(!0)}catch(e){x("Failed to save settings!","pojo-accessibility"),console.error(e)}},disabled:e,children:(0,l.__)("Save changes","pojo-accessibility")})})]})};if(9884==t.j)var fe=t(34627);if(9884==t.j)var ve=t(30431);if(9884==t.j)var _e=t(53955);if(9884==t.j)var Ae=t(55016);if(9884==t.j)var Ce=t(60765);const we=9884==t.j?()=>{const{openSidebar:e}=(0,w.t0)(),{loading:i,isElementorOne:t}=(0,w.e7)(),o=(0,Ae.A)({variant:"popover",popupId:"popupMenuExpandedSidebar"});return i&&!t?(0,c.jsx)(ke,{children:(0,c.jsx)(ve.A,{width:"100%",height:91})}):e?(0,c.jsx)(r.f,{}):(0,c.jsxs)(ke,{children:[(0,c.jsx)(_e.A,{title:(0,l.__)("My account","site-mailer"),placement:"right",children:(0,c.jsxs)(Ie,{...(0,Ce.cO)(o),children:[(0,c.jsx)(fe.A,{sx:{color:"common.black"}}),(0,c.jsx)(r.ID,{})]})}),(0,c.jsx)(r.in,{...(0,Ce.NA)(o),closeAction:o.close,anchorOrigin:{vertical:"bottom",horizontal:"right"},transformOrigin:{vertical:"top",horizontal:"left"}})]})}:null,ke=(0,a.I)(o.A)`
	display: flex;
	flex-direction: row;
	align-items: start;
	justify-content: center;

	margin: ${({theme:e})=>e.spacing(2)};
	padding: 0;

	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
`,Ie=(0,a.I)(X.A)`
	padding: ${({theme:e})=>e.spacing(1)};
	background-color: ${({theme:e})=>e.palette.background.paper};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;

	&:hover {
		background-color: ${({theme:e})=>e.palette.action.hover};
	}

	&:focus {
		background-color: ${({theme:e})=>e.palette.action.focus};
	}
`;if(9884==t.j)var De=t(70320);if(9884==t.j)var Se=t(12332);var Ee=t(59921),Me=t(5522);if(9884==t.j)var Pe=t(74565);const $e=9884==t.j?()=>{const{planUsage:e,planData:i,dismissedQuotaNotices:t,setDismissedQuotaNotices:o}=(0,w.t0)(),{save:s}=(0,w.rX)(),n="Free"===i?.plan?.name,{isElementorOne:a}=(0,J.F)(),r=e=>{k.K.sendEvent(k.m.upgradeButtonClicked,{feature:"quota notice "+e,component:"upgrade button"}),(0,Pe.Fe)((0,Me.b)(Ee.qQ[`UPGRADE_${e}`]))},d=e=>{k.K.sendEvent("quota_notice_triggered",{quota_level:e})},p=async e=>{const i=t.includes(e)?t:[...t,e];o(i);try{await s({ea11y_dismissed_quota_notices:i})}catch(e){console.error("Failed to save dismissed notice:",e)}};if(a)return null;if(e.aiCredits<80&&e.scannedPages<80)return null;if(e.aiCredits>=80&&e.aiCredits<95&&!n||e.scannedPages>=80&&e.scannedPages<95&&!n){const e="quota-banner-80";return t.includes(e)?null:(d("80%"),(0,c.jsxs)(Re,{severity:"warning",square:!0,onClose:()=>p(e),children:[(0,c.jsx)(Se.A,{children:(0,l.__)("You've reached 80% of your monthly plan usage","pojo-accessibility")}),(0,l.__)("Upgrade now to increase your limit and ensure all accessibility features stay fully available for every visitor.","pojo-accessibility"),(0,c.jsx)(De.A,{variant:"outlined",onClick:()=>r("80"),sx:{marginTop:1},children:(0,l.__)("Upgrade now","pojo-accessibility")})]}))}if(e.aiCredits>=95&&e.aiCredits<100&&!n||e.scannedPages>=95&&e.scannedPages<100&&!n){const e="quota-banner-95";return t.includes(e)?null:(d("95%"),(0,c.jsxs)(Re,{severity:"error",square:!0,onClose:()=>p(e),children:[(0,c.jsx)(Se.A,{children:(0,l.__)("Only 5% of your monthly plan usage left","pojo-accessibility")}),(0,l.__)("Upgrade now to increase your limit and keep all accessibility features running smoothly for every visitor.","pojo-accessibility"),(0,c.jsx)(De.A,{variant:"outlined",onClick:()=>r("95"),sx:{marginTop:1},children:(0,l.__)("Upgrade now","pojo-accessibility")})]}))}if((100===e.aiCredits||100===e.scannedPages)&&!n){const e="quota-banner-100";return t.includes(e)?null:(d("100%"),(0,c.jsxs)(Re,{severity:"error",square:!0,onClose:()=>p(e),children:[(0,c.jsx)(Se.A,{children:(0,l.__)("You've reached your monthly plan usage","pojo-accessibility")}),(0,l.__)("Upgrade now to raise your limit and maintain complete access to all accessibility features for every visitor.","pojo-accessibility"),(0,c.jsx)(De.A,{variant:"outlined",onClick:()=>r("100"),sx:{marginTop:1},children:(0,l.__)("Upgrade now","pojo-accessibility")})]}))}if(100===e.scannedPages&&n){const e="quota-banner-100-free";return t.includes(e)?null:(d("100%"),(0,c.jsxs)(Re,{severity:"error",square:!0,onClose:()=>p(e),children:[(0,c.jsx)(Se.A,{children:(0,l.__)("You've reached your free plan limit","pojo-accessibility")}),(0,l.__)("Upgrade to scan more pages, unlock AI fixes, and access all accessibility features.","pojo-accessibility"),(0,c.jsx)(De.A,{variant:"outlined",onClick:()=>r("100"),sx:{marginTop:1},children:(0,l.__)("Upgrade now","pojo-accessibility")})]}))}}:null,Re=(0,a.I)(D.Ay)`
	.MuiAlert-content div {
		display: flex;
		flex-direction: column;
		align-items: start;
	}
`,ze=()=>{const{widgetMenuSettings:e,planData:i}=(0,w.t0)(),{toggleMenu:t}=(0,w.Yd)(),o=()=>{const e=T.A_.REMOVE_BRANDING.replaceAll("-","_");return i?.plan?.features?.[e]};return(0,c.jsx)(S.A,{variant:"outlined",children:(0,c.jsx)(M.A,{title:(0,c.jsxs)(c.Fragment,{children:[(0,l.__)("Ally by Elementor logo","pojo-accessibility"),!o()&&(0,c.jsx)(r.wT,{childKey:T.A_.REMOVE_BRANDING,childValue:"pro",source:"icon",showIcon:!0})]}),subheader:(0,c.jsx)(n.A,{variant:"body2",children:(0,l.__)("Hide the Elementor logo from the bottom of your widget menu.","pojo-accessibility")}),action:(0,c.jsx)(r.wT,{childKey:T.A_.REMOVE_BRANDING,childValue:"pro",source:"toggle",enabled:o(),children:(0,c.jsx)(r.Yq,{disabled:!o(),onChange:()=>t("pro",T.A_?.REMOVE_BRANDING),checked:e?.[T.A_?.REMOVE_BRANDING]?.enabled||!1})})})})}},41094(e,i,t){t.d(i,{Nc:()=>u,QP:()=>h,TE:()=>x,ax:()=>p,fM:()=>c,rf:()=>d});var o=t(78048),s=t(86269),n=t(71676),a=t(94284),r=t(85848),l=t(95231);const c=(0,l.I)(o.A)`
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	align-items: center;
	max-height: 100%;
	min-height: 50%;
	height: 100%;
	overflow: hidden;
`,d=(0,l.I)(n.A)`
	overflow: auto;
	max-height: 100%;
	padding: ${({theme:e})=>e.spacing(4)};
`,p=(0,l.I)(o.A)`
	overflow: auto;
	max-height: 100%;
	padding: ${({theme:e})=>e.spacing(4)};
	width: 100%;
`,h=(0,l.I)(r.A)`
	display: flex;
	align-items: center;

	margin: 0;

	color: ${({theme:e})=>e.palette.common.black};
	line-height: 1.75;

	.MuiChip-root {
		margin-inline-start: ${({theme:e})=>e.spacing(1)};
		font-weight: 400;
	}
`,x=(0,l.I)(a.A)`
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	padding: 24px;
	width: 376px;
	min-height: 264px;
	box-shadow: ${({theme:e})=>e.shadows[0]};
	cursor: pointer;

	:hover {
		box-shadow: 0 0 15px 0 rgb(37 99 235 / 0.15);
		border-color: ${({theme:e})=>e.palette.info.main};
	}
`,u=((0,l.I)(n.A)`
	display: flex;
	flex-direction: column;
	align-items: start;
	gap: ${({theme:e})=>e.spacing(2)};
	overflow: auto;
	max-height: 100%;
	max-width: 1200px;
	width: 100%;
	margin-right: auto;
	margin-left: auto;

	@media (min-width: ${({theme:e})=>e.breakpoints.values.sm}px) {
		padding-left: 0;
		padding-right: 0;
	}
`,(0,l.I)(s.A)`
	&:last-child {
		padding-bottom: 16px;
	}
`)},42679(e,i,t){t.d(i,{F:()=>d,c:()=>c});var o=t(208),s=t(86087),n=t(27723),a=t(47064),r=t(10790);const l=(0,s.createContext)({}),c=({children:e})=>{const{error:i}=(0,o.m)(),[t,c]=(0,s.useState)(),[d,p]=(0,s.useState)(!1),h=(0,s.useCallback)(()=>{a.A.getPluginSettings().then(e=>{"isConnected"in e&&(e.isConnected=Boolean(e.isConnected)),"closePostConnectModal"in e&&(e.closePostConnectModal=Boolean(e.closePostConnectModal)),"closeOnboardingModal"in e&&(e.closeOnboardingModal=Boolean(e.closeOnboardingModal)),"closeGetStartedModal"in e&&(e.closeGetStartedModal=Boolean(e.closeGetStartedModal)),"isUrlMismatch"in e&&(e.isUrlMismatch=Boolean(e.isUrlMismatch)),"unfilteredUploads"in e&&(e.unfilteredUploads=Boolean(e.unfilteredUploads)),"homeUrl"in e&&(e.homeUrl=e.homeUrl),"isElementorOne"in e&&(e.isElementorOne=Boolean(e.isElementorOne)),c(e),p(!0)}).catch(()=>{i((0,n.__)("An error occurred.","pojo-accessibility")),p(!0)})},[]);return(0,s.useEffect)(()=>{h()},[h]),(0,r.jsx)(l.Provider,{value:{...t,loaded:d,refreshPluginSettings:h},children:e})},d=()=>(0,s.useContext)(l)},43331(e,i,t){t.d(i,{Ph:()=>M,xT:()=>E,A_:()=>I,Vb:()=>C,pp:()=>S,oG:()=>w,no:()=>k});var o=t(77484),s=t(42587),n=t(21627),a=t(66751),r=t(18614),l=t(3072),c=t(10790);const d=(e,{size:i})=>(0,c.jsxs)(l.A,{viewBox:"0 0 24 24",fill:"none",stroke:e.sx.color,strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",fontSize:i,children:[(0,c.jsx)("path",{d:"M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"}),(0,c.jsx)("path",{d:"M4 8v-2a2 2 0 0 1 2 -2h2"})," ",(0,c.jsx)("path",{d:"M4 16v2a2 2 0 0 0 2 2h2"}),(0,c.jsx)("path",{d:"M16 4h2a2 2 0 0 1 2 2v2"}),(0,c.jsx)("path",{d:"M16 20h2a2 2 0 0 0 2 -2v-2"})]});var p=t(43152),h=t(76811);const x=(e,{size:i})=>(0,c.jsx)(l.A,{viewBox:"0 0 22 20",fontSize:i,...e,children:(0,c.jsx)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M1.46967 0.46967C1.76256 0.176777 2.23744 0.176777 2.53033 0.46967L16.8174 14.7568C16.8654 14.7941 16.9095 14.8378 16.9483 14.8876L20.5303 18.4697C20.8232 18.7626 20.8232 19.2374 20.5303 19.5303C20.2374 19.8232 19.7626 19.8232 19.4697 19.5303L16.2465 16.3072C14.6369 17.2662 12.8851 17.7501 11 17.7501C6.63999 17.7501 3.09715 15.1815 0.348826 10.3722C0.217043 10.1416 0.217059 9.85846 0.348868 9.62787C1.57253 7.48713 2.95235 5.78794 4.50007 4.56074L1.46967 1.53033C1.17678 1.23744 1.17678 0.762563 1.46967 0.46967ZM5.56879 5.62945C4.2411 6.64776 3.00403 8.09495 1.86833 10.0001C4.41111 14.2678 7.46133 16.2501 11 16.2501C12.4819 16.2501 13.8599 15.904 15.1467 15.2073L12.3414 12.402C11.9342 12.6299 11.4719 12.7526 10.997 12.7524C10.2675 12.7522 9.56806 12.4622 9.05245 11.9462C8.53683 11.4302 8.2473 10.7305 8.24756 10.0011C8.24773 9.52682 8.37037 9.06524 8.59796 8.65862L5.56879 5.62945ZM9.76068 9.82134C9.75201 9.88068 9.74758 9.94091 9.74756 10.0016C9.74744 10.3332 9.87907 10.6513 10.1135 10.8859C10.3479 11.1205 10.6659 11.2523 10.9975 11.2524C11.0585 11.2525 11.119 11.248 11.1787 11.2393L9.76068 9.82134ZM10.9973 3.75005C10.1762 3.74715 9.35876 3.86029 8.56927 4.08614C8.17104 4.20006 7.75585 3.96957 7.64192 3.57133C7.528 3.17309 7.75849 2.75791 8.15672 2.64398C9.08165 2.37939 10.0393 2.24678 11.0013 2.25006C15.3607 2.25058 18.9031 4.81912 21.6512 9.62794C21.783 9.85855 21.7829 10.1417 21.6511 10.3723C20.8503 11.7731 19.984 12.9842 19.0478 13.9971C18.7666 14.3013 18.2921 14.32 17.9879 14.0388C17.6838 13.7577 17.6651 13.2832 17.9462 12.979C18.7114 12.1512 19.4404 11.1597 20.1317 10C17.5889 5.73236 14.5387 3.75006 11 3.75006L10.9973 3.75005Z"})});var u=t(95092),g=t(131),m=t(28426),b=t(81976),j=t(52805);const y=()=>(0,c.jsxs)("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",children:[(0,c.jsx)("path",{d:"M17 8C17.621 8.46574 18.125 9.06966 18.4721 9.76393C18.8193 10.4582 19 11.2238 19 12C19 12.7762 18.8193 13.5418 18.4721 14.2361C18.125 14.9303 17.621 15.5343 17 16",stroke:"black",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,c.jsx)("path",{d:"M8 15.0002H6C5.73478 15.0002 5.48043 14.8949 5.29289 14.7073C5.10536 14.5198 5 14.2654 5 14.0002V10.0002C5 9.735 5.10536 9.48065 5.29289 9.29311C5.48043 9.10557 5.73478 9.00022 6 9.00022H8L11.5 4.50022C11.5874 4.33045 11.7326 4.19754 11.9095 4.12551C12.0863 4.05348 12.2831 4.04708 12.4643 4.10746C12.6454 4.16784 12.799 4.29103 12.8972 4.45476C12.9955 4.61849 13.0319 4.81196 13 5.00022V19.0002C13.0319 19.1885 12.9955 19.3819 12.8972 19.5457C12.799 19.7094 12.6454 19.8326 12.4643 19.893C12.2831 19.9534 12.0863 19.947 11.9095 19.8749C11.7326 19.8029 11.5874 19.67 11.5 19.5002L8 15.0002Z",stroke:"black",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})]}),f=(e,{size:i})=>(0,c.jsx)(l.A,{viewBox:"0 0 40 41",fontSize:i,...e,children:(0,c.jsx)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M18.3333 6.75C17.1827 6.75 16.25 7.68274 16.25 8.83333V12.1667C16.25 13.3173 17.1827 14.25 18.3333 14.25H21.6667C22.8173 14.25 23.75 13.3173 23.75 12.1667V8.83333C23.75 7.68274 22.8173 6.75 21.6667 6.75H18.3333ZM21.25 16.75H21.6667C24.198 16.75 26.25 14.698 26.25 12.1667V8.83333C26.25 6.30203 24.198 4.25 21.6667 4.25H18.3333C15.802 4.25 13.75 6.30203 13.75 8.83333V12.1667C13.75 14.698 15.802 16.75 18.3333 16.75H18.75V19.25H13.3333C12.1178 19.25 10.952 19.7329 10.0924 20.5924C9.23289 21.452 8.75 22.6178 8.75 23.8333V24.25H8.33333C5.80203 24.25 3.75 26.302 3.75 28.8333V32.1667C3.75 34.698 5.80203 36.75 8.33333 36.75H11.6667C14.198 36.75 16.25 34.698 16.25 32.1667V28.8333C16.25 26.302 14.198 24.25 11.6667 24.25H11.25V23.8333C11.25 23.2808 11.4695 22.7509 11.8602 22.3602C12.2509 21.9695 12.7808 21.75 13.3333 21.75H26.6667C27.2192 21.75 27.7491 21.9695 28.1398 22.3602C28.5305 22.7509 28.75 23.2808 28.75 23.8333V24.25H28.3333C25.802 24.25 23.75 26.302 23.75 28.8333V32.1667C23.75 34.698 25.802 36.75 28.3333 36.75H31.6667C34.198 36.75 36.25 34.698 36.25 32.1667V28.8333C36.25 26.302 34.198 24.25 31.6667 24.25H31.25V23.8333C31.25 22.6178 30.7671 21.452 29.9076 20.5924C29.048 19.7329 27.8822 19.25 26.6667 19.25H21.25V16.75ZM28.3333 26.75C27.1827 26.75 26.25 27.6827 26.25 28.8333V32.1667C26.25 33.3173 27.1827 34.25 28.3333 34.25H31.6667C32.8173 34.25 33.75 33.3173 33.75 32.1667V28.8333C33.75 27.6827 32.8173 26.75 31.6667 26.75H28.3333ZM8.33333 26.75C7.18274 26.75 6.25 27.6827 6.25 28.8333V32.1667C6.25 33.3173 7.18274 34.25 8.33333 34.25H11.6667C12.8173 34.25 13.75 33.3173 13.75 32.1667V28.8333C13.75 27.6827 12.8173 26.75 11.6667 26.75H8.33333Z",fill:"black"})}),v=(e,{size:i})=>(0,c.jsx)(l.A,{viewBox:"0 0 16 20",fontSize:i,...e,children:(0,c.jsx)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M3 1.75C2.66848 1.75 2.35054 1.8817 2.11612 2.11612C1.8817 2.35054 1.75 2.66848 1.75 3V17C1.75 17.3315 1.8817 17.6495 2.11612 17.8839C2.35054 18.1183 2.66848 18.25 3 18.25H13C13.3315 18.25 13.6495 18.1183 13.8839 17.8839C14.1183 17.6495 14.25 17.3315 14.25 17V6.75H11C10.5359 6.75 10.0908 6.56563 9.76256 6.23744C9.43437 5.90925 9.25 5.46413 9.25 5V1.75H3ZM10.75 2.81066L13.1893 5.25H11C10.9337 5.25 10.8701 5.22366 10.8232 5.17678C10.7763 5.12989 10.75 5.0663 10.75 5V2.81066ZM1.05546 1.05546C1.57118 0.539731 2.27065 0.25 3 0.25H10C10.1989 0.25 10.3897 0.329018 10.5303 0.46967L15.5303 5.46967C15.671 5.61032 15.75 5.80109 15.75 6V17C15.75 17.7293 15.4603 18.4288 14.9445 18.9445C14.4288 19.4603 13.7293 19.75 13 19.75H3C2.27065 19.75 1.57118 19.4603 1.05546 18.9445C0.539731 18.4288 0.25 17.7293 0.25 17V3C0.25 2.27065 0.539731 1.57118 1.05546 1.05546ZM4.25 7C4.25 6.58579 4.58579 6.25 5 6.25H6C6.41421 6.25 6.75 6.58579 6.75 7C6.75 7.41421 6.41421 7.75 6 7.75H5C4.58579 7.75 4.25 7.41421 4.25 7ZM4.25 11C4.25 10.5858 4.58579 10.25 5 10.25H11C11.4142 10.25 11.75 10.5858 11.75 11C11.75 11.4142 11.4142 11.75 11 11.75H5C4.58579 11.75 4.25 11.4142 4.25 11ZM4.25 15C4.25 14.5858 4.58579 14.25 5 14.25H11C11.4142 14.25 11.75 14.5858 11.75 15C11.75 15.4142 11.4142 15.75 11 15.75H5C4.58579 15.75 4.25 15.4142 4.25 15Z"})});var _=t(94885),A=t(27723);const C=Object.freeze({SPEED:2,FOREGROUND_COLOR:"#D9D9D9"}),w="ea11y-widget-preview--container",k="ea11y-root",I={REMOVE_BRANDING:"remove-elementor-label"},D={color:"black"},S=10,E={"bigger-text":{title:(0,A.__)("Bigger text","pojo-accessibility"),chartsTitle:(0,A.__)("Bigger text","pojo-accessibility"),icon:(0,c.jsx)(_.A,{sx:D})},"bigger-line-height":{title:(0,A.__)("Bigger line height","pojo-accessibility"),chartsTitle:(0,A.__)("Bigger line height","pojo-accessibility"),icon:(0,c.jsx)(u.A,{sx:D})},"text-align":{title:(0,A.__)("Text align","pojo-accessibility"),chartsTitle:(0,A.__)("Align text","pojo-accessibility"),icon:(0,c.jsx)(s.A,{sx:D})},"readable-font":{title:(0,A.__)("Readable font","pojo-accessibility"),chartsTitle:(0,A.__)("Readable font","pojo-accessibility"),icon:(0,c.jsx)(b.A,{sx:D})},grayscale:{title:(0,A.__)("Greyscale","pojo-accessibility"),chartsTitle:(0,A.__)("Greyscale","pojo-accessibility"),icon:(0,c.jsx)(p.A,{sx:D})},contrast:{title:(0,A.__)("Contrast","pojo-accessibility"),chartsTitle:(0,A.__)("Contrast","pojo-accessibility"),icon:(0,c.jsx)(r.A,{sx:D})},"page-structure":{title:(0,A.__)("Page structure","pojo-accessibility"),chartsTitle:(0,A.__)("Page structure","pojo-accessibility"),icon:(0,c.jsx)(g.A,{sx:D})},sitemap:{title:(0,A.__)("Sitemap","pojo-accessibility"),chartsTitle:(0,A.__)("Sitemap","pojo-accessibility"),icon:(0,c.jsx)(f,{sx:D})},"reading-mask":{title:(0,A.__)("Reading mask","pojo-accessibility"),chartsTitle:(0,A.__)("Reading mask","pojo-accessibility"),icon:(0,c.jsx)(j.A,{sx:D})},"hide-images":{title:(0,A.__)("Hide images","pojo-accessibility"),chartsTitle:(0,A.__)("Hide images","pojo-accessibility"),icon:(0,c.jsx)(h.A,{sx:D})},"pause-animations":{title:(0,A.__)("Pause animations","pojo-accessibility"),chartsTitle:(0,A.__)("Pause animations","pojo-accessibility"),icon:(0,c.jsx)(m.A,{sx:D})},"highlight-links":{title:(0,A.__)("Highlight links","pojo-accessibility"),chartsTitle:(0,A.__)("Highlight links","pojo-accessibility"),icon:(0,c.jsx)(n.A,{sx:D})},"focus-outline":{title:(0,A.__)("Outline focus","pojo-accessibility"),chartsTitle:(0,A.__)("Outline focus","pojo-accessibility"),icon:(0,c.jsx)(d,{sx:D})},"hide-widget":{title:(0,A.__)("Hide widget","pojo-accessibility"),chartsTitle:(0,A.__)("Hide widget","pojo-accessibility"),icon:(0,c.jsx)(x,{})},statement:{title:(0,A.__)("Statement","pojo-accessibility"),chartsTitle:(0,A.__)("Statement","pojo-accessibility"),icon:(0,c.jsx)(v,{})},"screen-reader":{title:(0,A.__)("Screen reader","pojo-accessibility"),chartsTitle:(0,A.__)("Screen reader","pojo-accessibility"),icon:(0,c.jsx)(y,{}),pro:!0},"language-selector":{title:(0,A.__)("Language selector","pojo-accessibility"),chartsTitle:(0,A.__)("Language selector","pojo-accessibility"),icon:(0,c.jsx)(a.A,{sx:D}),pro:!0},other:{title:(0,A.__)("Other","pojo-accessibility"),chartsTitle:(0,A.__)("Other","pojo-accessibility")}},M=[o.ColorBurgundy900,o.ColorPink400,o.ColorBlue700,o.ColorBlue500,o.ColorGreen700,o.ColorGreen500,o.ColorCyan400]},46097(e,i,t){t.d(i,{PL:()=>Xe,or:()=>De,dT:()=>He,uM:()=>Vi,sk:()=>Ce,TM:()=>Gi.A,Mj:()=>mi,pR:()=>Qi,Yq:()=>Ni,Lp:()=>yi,y2:()=>Ai,Yr:()=>ei,qi:()=>Xi,ev:()=>be,Ex:()=>re,lK:()=>N,Dr:()=>mt,c:()=>Te.c,O7:()=>ot,in:()=>M,Zh:()=>ze,cy:()=>wi,wT:()=>Fi,En:()=>lt,f:()=>Et,ID:()=>ht,Nt:()=>B,wZ:()=>V,Dg:()=>qe,gp:()=>pi,o7:()=>Ki,Ov:()=>ti});var o=t(79945),s=t(95231),n=t(9626),a=t(59921),r=t(70276),l=t(27723),c=t(10790);if((0,s.I)(o.A)`
	justify-content: center;
	padding: ${({theme:e})=>`${e.spacing(1)} ${e.spacing(3)}`};
`,9884==t.j)var d=t(34627);if(9884==t.j)var p=t(80984);if(9884==t.j)var h=t(65463);if(9884==t.j)var x=t(98952);if(9884==t.j)var u=t(65931);var g=t(78048);if(9884==t.j)var m=t(50602);if(9884==t.j)var b=t(92810);if(9884==t.j)var j=t(29350);if(9884==t.j)var y=t(92272);var f=t(92182);if(9884==t.j)var v=t(91116);if(9884==t.j)var _=t(53955);var A=t(85848),C=t(50438),w=t(208),k=t(5522),I=t(47064),D=t(42679);if(9884==t.j)var S=t(62883);if(9884==t.j)var E=t(74565);const M=9884==t.j?({closeAction:e,showUpgradeButton:i,...t})=>{const{save:o}=(0,n.rX)(),{error:s}=(0,w.m)(),{planData:f}=(0,n.t0)(),{isElementorOne:M}=(0,D.F)(),T="Free"===f?.plan?.name;return(0,c.jsxs)(y.A,{...t,PaperProps:{sx:{backgroundColor:"common.white",minWidth:224}},autoFocus:!1,children:[f?.plan&&(0,c.jsxs)($,{children:[(0,c.jsx)(u.A,{sx:{width:24,height:24},children:(0,c.jsx)(x.A,{sx:{color:"common.white"}})}),(0,c.jsxs)(g.A,{display:"flex",flexDirection:"column",gap:.5,children:[(0,c.jsx)(_.A,{title:f?.user?.email,children:(0,c.jsx)(A.A,{variant:"caption",color:"text.primary",children:(0,S.b)(f?.user?.email)})}),f?.plan?.name&&(0,c.jsx)(b.A,{variant:"filled",label:f?.plan?.name,size:"tiny",sx:{width:"fit-content"}})]})]}),f?.plan&&(0,c.jsx)(v.A,{disableGutters:!0,children:(0,c.jsx)(j.A,{sx:{my:1}})}),!M&&f?.plan&&(0,c.jsxs)(R,{as:"li",children:[(0,c.jsx)(lt,{type:"scanner",quotaData:f?.scannedPages}),(0,c.jsx)(lt,{type:"ai",quotaData:f?.aiCredits}),!i&&(0,c.jsx)(m.A,{variant:"outlined",startIcon:T?(0,c.jsx)(p.A,{}):null,onClick:()=>{r.K.sendEvent(r.m.upgradeButtonClicked,{feature:"upgrade plan",component:"my account popup"}),(0,E.Fe)((0,k.b)(a.qQ.ADD_VISITS))},size:"small",fullWidth:!0,color:T?"promotion":"secondary",sx:{marginTop:.5},children:T?(0,l.__)("Upgrade plan","pojo-accessibility"):(0,l.__)("View more plans","pojo-accessibility")})]}),!M&&f?.plan&&(0,c.jsx)(v.A,{disableGutters:!0,children:(0,c.jsx)(j.A,{sx:{my:1}})}),(0,c.jsxs)(P,{dense:!0,onClick:async()=>{try{await I.A.deactivate(),await I.A.redirectToConnect(),await o({ea11y_close_post_connect_modal:!1}),r.K.sendEvent(r.m.menuButtonClicked,{buttonName:"Switch account"})}catch(e){s((0,l.__)("Failed to switch account. Please try again.","pojo-accessibility")),console.error(e)}},children:[(0,c.jsx)(C.Sv,{sx:{color:"action.active"}}),(0,c.jsx)(z,{children:(0,l.__)("Switch account","pojo-accessibility")})]}),(0,c.jsxs)(P,{dense:!0,onClick:()=>{r.K.sendEvent(r.m.menuButtonClicked,{buttonName:"Deactivate subscription"}),(0,E.Fe)(a.qQ.MANAGE_SUBSCRIPTION.replace("{subscriptionId}/",""))},children:[(0,c.jsx)(h.A,{sx:{color:"action.active"}}),(0,c.jsx)(z,{children:(0,l.__)("Deactivate subscription","pojo-accessibility")})]}),(0,c.jsx)(v.A,{disableGutters:!0,children:(0,c.jsx)(j.A,{sx:{my:1}})}),(0,c.jsxs)(P,{dense:!0,onClick:()=>{r.K.sendEvent(r.m.menuButtonClicked,{buttonName:"Manage subscription"}),(0,E.Fe)(a.qQ.MANAGE_SUBSCRIPTION.replace("{subscriptionId}",f?.plan?.subscription_id))},children:[(0,c.jsx)(d.A,{sx:{color:"action.active"}}),(0,c.jsx)(z,{children:(0,l.__)("Manage subscription","pojo-accessibility")})]})]})}:null,P=(0,s.I)(f.A)`
	gap: ${({theme:e})=>e.spacing(1)};

	&.MuiMenuItem-gutters:focus,
	&.MuiMenuItem-gutters:focus-visible {
		box-shadow: inset 0 0 0 3px #5e9ed6;
	}
`,$=(0,s.I)(f.A)`
	display: flex;
	flex-direction: row;
	align-items: center;
	justify-content: flex-start;

	gap: ${({theme:e})=>e.spacing(1.5)};
	padding: ${({theme:e})=>e.spacing(.5,2)};
`,R=(0,s.I)(g.A)`
	display: flex;
	flex-direction: column;
	gap: ${({theme:e})=>e.spacing(.5)};
	padding: ${({theme:e})=>e.spacing(1,2)};
`,z=(0,s.I)(A.A)`
	color: ${({theme:e})=>e.palette.text.primary};
	font-size: 14px;
`;(0,s.I)(f.A)`
	&.MuiMenuItem-gutters:focus,
	&.MuiMenuItem-gutters:focus-visible {
		box-shadow: inset 0 0 0 3px #5e9ed6;
	}
`,(0,s.I)(A.A)`
	color: ${({theme:e})=>e.palette.text.primary};
	font-size: 14px;
`;var T=t(16956);const B=9884==t.j?()=>{const{openSidebar:e}=(0,n.t0)();return(0,c.jsxs)(H,{children:[(0,c.jsx)(L,{"aria-hidden":!0,sx:{margin:e?0:"0 auto"}}),e&&(0,c.jsx)(A.A,{variant:"subtitle1",as:"div",children:(0,l.__)("Accessibility","pojo-accessibility")})]})}:null,H=(0,s.I)("div")`
	display: flex;
	align-items: center;
	gap: ${({theme:e})=>e.spacing(1.5)};
	white-space: nowrap;
	padding: 0;
`,L=(0,s.I)(T.A)`
	font-size: 2.5rem;
	padding: ${({theme:e})=>e.spacing(.75)};
	border: 1px solid ${({theme:e})=>e.palette.divider};
	border-radius: ${({theme:e})=>2*e.shape.borderRadius}px;
	color: rgb(0 0 0 / 0.54);
`;if(9884==t.j)var O=t(95726);const V=9884==t.j?()=>(0,c.jsx)(O.A,{disablePadding:!0,children:Object.entries(Te.c).map(([e,i])=>(0,c.jsx)(mt,{keyName:e,item:i},e))}):null;var F=t(86752),Z=t(99028),W=t(84162);const N=e=>{const{iconDesign:i,updateIconDesign:t}=(0,n.vS)(),o=e=>()=>{t({size:e}),r.K.sendEvent(r.m.sizeTypeClicked,{size:e})};return(0,c.jsxs)(F.A,{children:[(0,c.jsx)(Z.A,{id:"icon-size-radio-buttons-group-label",color:"secondary",children:(0,c.jsx)(A.A,{variant:"subtitle2",marginBottom:1,children:(0,l.__)("Size","pojo-accessibility")})}),(0,c.jsx)(W.A,{...e,"aria-labelledby":"icon-size-radio-buttons-group-label",name:"icon-size-radio-buttons-group",value:i.size,sx:{display:"flex",flexDirection:"row",gap:2},children:[{value:"large",fontSize:64},{value:"medium",fontSize:44},{value:"small",fontSize:36}].map(e=>(0,c.jsx)(Xi,{iconType:"size",option:e,clickHandler:o},e.value))})]})};var G=t(3072);const K=function({size:e},i){const{iconDesign:t}=(0,n.t0)(),o=`lch(from ${t?.color||"#fff"} calc((50 - l) * 100) 0 0)`;return(0,c.jsx)(G.A,{viewBox:"0 0 22 22",fill:"none",sx:{marginRight:0,fontSize:e,paddingRight:"1px"},...i,children:(0,c.jsx)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M10.6776 1.86411C11.3515 1.19015 12.2656 0.811523 13.2188 0.811523C14.1719 0.811523 15.086 1.19015 15.7599 1.86411C16.0888 2.19301 16.3474 2.57911 16.5259 2.99902H19.7812C20.5579 2.99902 21.1875 3.62862 21.1875 4.40527C21.1875 5.18192 20.5579 5.81152 19.7812 5.81152H16.5259C16.3474 6.23144 16.0888 6.61753 15.7599 6.94644C15.086 7.6204 14.1719 7.99902 13.2188 7.99902C12.2656 7.99902 11.3515 7.6204 10.6776 6.94644C10.3487 6.61753 10.0901 6.23144 9.91156 5.81152H2.28125C1.5046 5.81152 0.875 5.18192 0.875 4.40527C0.875 3.62862 1.5046 2.99902 2.28125 2.99902H9.91156C10.0901 2.57911 10.3487 2.19301 10.6776 1.86411ZM13.2188 3.62402C13.0116 3.62402 12.8128 3.70633 12.6663 3.85285C12.5198 3.99936 12.4375 4.19807 12.4375 4.40527C12.4375 4.61247 12.5198 4.81119 12.6663 4.9577C12.8128 5.10421 13.0115 5.18652 13.2188 5.18652C13.426 5.18652 13.6247 5.10421 13.7712 4.9577C13.9177 4.81119 14 4.61247 14 4.40527C14 4.19807 13.9177 3.99936 13.7712 3.85285C13.6247 3.70633 13.426 3.62402 13.2188 3.62402ZM4.11508 8.42661C4.78904 7.75265 5.70313 7.37402 6.65625 7.37402C7.60937 7.37402 8.52346 7.75265 9.19741 8.42661C9.52632 8.75551 9.78489 9.14161 9.96344 9.56152H19.7812C20.5579 9.56152 21.1875 10.1911 21.1875 10.9678C21.1875 11.7444 20.5579 12.374 19.7812 12.374H9.96344C9.78489 12.7939 9.52632 13.18 9.19741 13.5089C8.52346 14.1829 7.60937 14.5615 6.65625 14.5615C5.70313 14.5615 4.78904 14.1829 4.11508 13.5089C3.78618 13.18 3.52761 12.7939 3.34906 12.374H2.28125C1.5046 12.374 0.875 11.7444 0.875 10.9678C0.875 10.1911 1.5046 9.56152 2.28125 9.56152H3.34906C3.52761 9.14161 3.78618 8.75551 4.11508 8.42661ZM6.65625 10.1865C6.44905 10.1865 6.25034 10.2688 6.10382 10.4153C5.95731 10.5619 5.875 10.7606 5.875 10.9678C5.875 11.175 5.95731 11.3737 6.10382 11.5202C6.25033 11.6667 6.44905 11.749 6.65625 11.749C6.86345 11.749 7.06216 11.6667 7.20868 11.5202C7.35519 11.3737 7.4375 11.175 7.4375 10.9678C7.4375 10.7606 7.35519 10.5619 7.20868 10.4153C7.06216 10.2688 6.86345 10.1865 6.65625 10.1865ZM13.9588 14.9891C14.6328 14.3152 15.5469 13.9365 16.5 13.9365C17.4531 13.9365 18.3672 14.3152 19.0412 14.9891C19.3701 15.3181 19.6287 15.7043 19.8073 16.1243C20.5719 16.1381 21.1875 16.7623 21.1875 17.5303C21.1875 18.2982 20.5719 18.9224 19.8073 18.9363C19.6287 19.3563 19.3701 19.7425 19.0412 20.0714C18.3672 20.7454 17.4531 21.124 16.5 21.124C15.5469 21.124 14.6328 20.7454 13.9588 20.0714C13.6299 19.7425 13.3714 19.3564 13.1928 18.9365H2.28125C1.5046 18.9365 0.875 18.3069 0.875 17.5303C0.875 16.7536 1.5046 16.124 2.28125 16.124H13.1928C13.3714 15.7041 13.6299 15.318 13.9588 14.9891ZM16.5 16.749C16.2928 16.749 16.0941 16.8313 15.9476 16.9778C15.8011 17.1244 15.7188 17.3231 15.7188 17.5303C15.7188 17.7375 15.8011 17.9362 15.9476 18.0827C16.0941 18.2292 16.2928 18.3115 16.5 18.3115C16.7072 18.3115 16.9059 18.2292 17.0524 18.0827C17.1989 17.9362 17.2812 17.7375 17.2812 17.5303C17.2812 17.3231 17.1989 17.1244 17.0524 16.9778C16.9059 16.8313 16.7072 16.749 16.5 16.749Z",fill:o})})},U=function({size:e},i){const{iconDesign:t}=(0,n.t0)(),o=`lch(from ${t?.color||"#fff"} calc((50 - l) * 100) 0 0)`;return(0,c.jsx)(G.A,{viewBox:"0 0 24 24",fill:"none",sx:{fontSize:e},...i,children:(0,c.jsx)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M.44.44a1.5 1.5 0 0 1 2.12 0l15.855 15.853c.073.06.141.128.203.204l4.943 4.942a1.5 1.5 0 0 1-2.122 2.122l-4.246-4.247a11.67 11.67 0 0 1-5.198 1.186c-4.881-.002-8.803-2.764-11.781-7.728a1.5 1.5 0 0 1 0-1.544C1.4 9.25 2.738 7.614 4.24 6.363L.44 2.56a1.5 1.5 0 0 1 0-2.122Zm5.934 8.055C5.288 9.355 4.25 10.512 3.27 12c2.513 3.821 5.427 5.5 8.729 5.5h.01a8.67 8.67 0 0 0 2.892-.476l-1.467-1.468a3.833 3.833 0 0 1-4.99-4.99l-2.071-2.07ZM11.996 6.5a9.064 9.064 0 0 0-1.822.18 1.5 1.5 0 0 1-.595-2.94c.798-.162 1.61-.242 2.423-.24 4.882 0 8.805 2.763 11.784 7.728a1.5 1.5 0 0 1 0 1.544c-.823 1.373-1.719 2.579-2.69 3.607a1.5 1.5 0 1 1-2.18-2.061A16.86 16.86 0 0 0 20.729 12C18.216 8.179 15.302 6.5 12 6.5h-.004Z",fill:o})})},q=function({size:e},i){const{iconDesign:t}=(0,n.t0)(),o=`lch(from ${t?.color||"#fff"} calc((50 - l) * 100) 0 0)`;return(0,c.jsx)(G.A,{viewBox:"0 0 19 25",fill:"none",sx:{marginRight:0,fontSize:e,paddingRight:"1px"},...i,children:(0,c.jsx)("path",{d:"m4.834 23.375 5-7.5m0 0 5 7.5m-5-7.5v-5m0 0 7.5-2.5m-7.5 2.5-7.5-2.5m8.75-5.5a1.25 1.25 0 1 1-2.5 0 1.25 1.25 0 0 1 2.5 0Z",stroke:o,strokeWidth:"3",strokeLinecap:"round",strokeLinejoin:"round"})})},Q=function({size:e},i){const{iconDesign:t}=(0,n.t0)(),o=`lch(from ${t?.color||"#fff"} calc((50 - l) * 100) 0 0)`;return(0,c.jsx)(G.A,{viewBox:"0 0 94 16",fill:"none",sx:{fontSize:3*e},...i,children:(0,c.jsx)("path",{d:"M5.64 2.57 2.548 12H.055L4.28.625h1.586L5.641 2.57ZM8.21 12l-3.1-9.43L4.86.625h1.6L10.71 12h-2.5Zm-.14-4.234v1.836H2.062V7.766H8.07ZM15.267 10.398c.276 0 .521-.052.735-.156.213-.11.38-.26.5-.453.125-.198.19-.43.195-.695h2.117a2.817 2.817 0 0 1-.477 1.586c-.312.458-.731.82-1.257 1.086-.526.26-1.115.39-1.766.39-.656 0-1.23-.11-1.719-.328a3.354 3.354 0 0 1-1.21-.906 3.973 3.973 0 0 1-.727-1.36 5.619 5.619 0 0 1-.242-1.671v-.227c0-.599.08-1.156.242-1.672.161-.52.404-.974.726-1.36.323-.39.727-.694 1.211-.913.485-.219 1.052-.328 1.703-.328.693 0 1.3.132 1.82.398.527.266.938.646 1.235 1.14.302.49.456 1.071.461 1.743h-2.117a1.762 1.762 0 0 0-.18-.766c-.11-.229-.27-.411-.484-.547-.209-.14-.466-.21-.774-.21-.328 0-.596.07-.804.21-.209.136-.37.323-.485.563a2.802 2.802 0 0 0-.242.805c-.041.296-.062.609-.062.937v.227c0 .328.02.643.062.945.042.302.12.57.235.805.12.234.283.419.492.554.208.136.479.203.812.203ZM23.76 10.398c.277 0 .522-.052.735-.156.214-.11.38-.26.5-.453.125-.198.19-.43.196-.695h2.117a2.817 2.817 0 0 1-.477 1.586c-.312.458-.732.82-1.258 1.086-.526.26-1.114.39-1.765.39-.656 0-1.23-.11-1.719-.328a3.355 3.355 0 0 1-1.21-.906 3.97 3.97 0 0 1-.727-1.36 5.616 5.616 0 0 1-.243-1.671v-.227c0-.599.081-1.156.243-1.672.161-.52.403-.974.726-1.36.323-.39.727-.694 1.211-.913.484-.219 1.052-.328 1.703-.328.693 0 1.3.132 1.82.398.527.266.938.646 1.235 1.14.302.49.456 1.071.46 1.743h-2.116a1.762 1.762 0 0 0-.18-.766c-.11-.229-.27-.411-.484-.547-.209-.14-.467-.21-.774-.21-.328 0-.596.07-.805.21-.208.136-.37.323-.484.563a2.8 2.8 0 0 0-.242.805 6.74 6.74 0 0 0-.063.937v.227c0 .328.021.643.063.945.041.302.12.57.234.805.12.234.284.419.492.554.209.136.48.203.813.203ZM32.653 12.156c-.656 0-1.245-.104-1.765-.312a3.878 3.878 0 0 1-1.329-.883 3.923 3.923 0 0 1-.828-1.305 4.391 4.391 0 0 1-.289-1.594V7.75c0-.64.091-1.227.274-1.758.182-.531.442-.992.78-1.383.345-.39.761-.69 1.25-.898.49-.214 1.043-.32 1.657-.32.6 0 1.13.099 1.594.296.463.198.851.48 1.164.844.318.365.557.802.719 1.313.161.505.242 1.067.242 1.687v.938h-6.719v-1.5h4.508v-.172a1.95 1.95 0 0 0-.172-.836 1.327 1.327 0 0 0-.5-.594c-.224-.146-.51-.219-.86-.219-.296 0-.551.066-.765.196a1.51 1.51 0 0 0-.523.547c-.13.234-.23.51-.297.828A5.268 5.268 0 0 0 30.7 7.75v.313a3 3 0 0 0 .14.937c.1.286.238.534.415.742.182.209.4.37.656.485.26.114.555.171.883.171a2.74 2.74 0 0 0 1.133-.234c.354-.161.658-.404.914-.726l1.093 1.187c-.177.255-.419.5-.726.734a3.88 3.88 0 0 1-1.094.579 4.512 4.512 0 0 1-1.46.218ZM42.123 9.664a.73.73 0 0 0-.14-.437c-.094-.13-.268-.25-.524-.36-.25-.114-.612-.219-1.086-.312a8.473 8.473 0 0 1-1.195-.352 4.145 4.145 0 0 1-.953-.523 2.276 2.276 0 0 1-.625-.72 2 2 0 0 1-.227-.96c0-.354.076-.688.227-1 .156-.313.378-.589.664-.828a3.142 3.142 0 0 1 1.063-.57c.421-.141.895-.211 1.421-.211.735 0 1.365.117 1.891.351.531.235.938.557 1.219.969.286.406.43.87.43 1.39h-2.25c0-.218-.047-.413-.141-.585a.929.929 0 0 0-.422-.414c-.188-.105-.432-.157-.734-.157-.25 0-.466.045-.649.133a.995.995 0 0 0-.422.344.827.827 0 0 0-.14.469c0 .13.026.247.078.351.057.1.148.19.273.274.125.083.287.161.485.234.203.068.453.13.75.188.609.125 1.153.289 1.632.492.48.198.86.468 1.141.812.281.339.422.784.422 1.336 0 .375-.083.719-.25 1.031a2.488 2.488 0 0 1-.719.82c-.312.23-.687.41-1.125.54-.432.125-.92.187-1.46.187-.787 0-1.454-.14-2-.422-.542-.28-.954-.638-1.235-1.07-.276-.437-.414-.885-.414-1.344h2.133c.01.308.088.555.234.742.151.188.341.323.57.407.235.083.487.125.758.125.292 0 .534-.04.727-.117.192-.084.338-.193.437-.329a.792.792 0 0 0 .156-.484ZM50.492 9.664a.731.731 0 0 0-.14-.437c-.094-.13-.269-.25-.524-.36-.25-.114-.612-.219-1.086-.312a8.469 8.469 0 0 1-1.195-.352 4.145 4.145 0 0 1-.953-.523 2.275 2.275 0 0 1-.625-.72 1.998 1.998 0 0 1-.227-.96c0-.354.076-.688.227-1 .156-.313.377-.589.664-.828a3.142 3.142 0 0 1 1.062-.57c.422-.141.896-.211 1.422-.211.735 0 1.365.117 1.89.351.532.235.938.557 1.22.969.286.406.43.87.43 1.39h-2.25c0-.218-.048-.413-.141-.585a.928.928 0 0 0-.422-.414c-.188-.105-.433-.157-.735-.157-.25 0-.466.045-.648.133a.994.994 0 0 0-.422.344.826.826 0 0 0-.14.469c0 .13.026.247.078.351.057.1.148.19.273.274.125.083.286.161.484.234.203.068.453.13.75.188a8.63 8.63 0 0 1 1.633.492c.48.198.86.468 1.14.812.282.339.423.784.423 1.336 0 .375-.084.719-.25 1.031a2.49 2.49 0 0 1-.72.82c-.312.23-.687.41-1.124.54-.432.125-.92.187-1.461.187-.786 0-1.453-.14-2-.422-.542-.28-.953-.638-1.234-1.07-.276-.437-.414-.885-.414-1.344h2.132c.01.308.089.555.235.742.15.188.34.323.57.407.234.083.487.125.758.125.292 0 .534-.04.726-.117.193-.084.339-.193.438-.329a.792.792 0 0 0 .156-.484ZM56.666 3.547V12h-2.258V3.547h2.258Zm-2.399-2.203c0-.328.115-.6.344-.813.23-.213.536-.32.922-.32.38 0 .685.107.914.32.234.214.351.485.351.813 0 .328-.117.599-.351.812-.23.214-.534.32-.914.32-.386 0-.693-.106-.922-.32a1.063 1.063 0 0 1-.344-.812ZM58.675 0h2.25v10.063L60.698 12h-2.023V0Zm7.602 7.688v.164c0 .63-.068 1.208-.204 1.734-.13.526-.333.982-.609 1.367-.276.38-.62.677-1.031.89-.406.21-.886.313-1.438.313-.515 0-.963-.104-1.343-.312a2.73 2.73 0 0 1-.946-.883 5.016 5.016 0 0 1-.617-1.336 10.16 10.16 0 0 1-.352-1.672v-.36a10.16 10.16 0 0 1 .352-1.671c.156-.51.362-.956.617-1.336.255-.38.57-.675.946-.883.375-.208.817-.312 1.328-.312.557 0 1.041.106 1.453.32.416.208.76.505 1.031.89.276.38.48.834.61 1.36.135.52.203 1.096.203 1.726Zm-2.25.164v-.165c0-.343-.026-.666-.079-.968a2.56 2.56 0 0 0-.25-.805 1.414 1.414 0 0 0-.492-.555c-.203-.135-.466-.203-.789-.203a1.77 1.77 0 0 0-.781.157c-.214.104-.39.25-.531.437a1.991 1.991 0 0 0-.305.672c-.068.255-.11.536-.125.844v1.023c.016.412.083.776.203 1.094.125.312.313.56.563.742.255.177.586.266.992.266.318 0 .58-.063.789-.188a1.26 1.26 0 0 0 .484-.531c.12-.23.203-.498.25-.805.047-.312.07-.65.07-1.015ZM70.2 3.547V12h-2.258V3.547H70.2Zm-2.398-2.203c0-.328.114-.6.343-.813.23-.213.537-.32.922-.32.38 0 .685.107.914.32.235.214.352.485.352.813 0 .328-.117.599-.352.812-.229.214-.534.32-.914.32-.385 0-.692-.106-.922-.32a1.062 1.062 0 0 1-.343-.812ZM74.584 0v12h-2.257V0h2.257ZM78.969 3.547V12H76.71V3.547h2.258ZM76.57 1.344c0-.328.115-.6.344-.813.23-.213.537-.32.922-.32.38 0 .685.107.914.32.234.214.352.485.352.813 0 .328-.118.599-.352.812-.23.214-.534.32-.914.32-.385 0-.693-.106-.922-.32a1.062 1.062 0 0 1-.344-.812ZM85.119 3.547V5.14h-4.922V3.547h4.922ZM81.416 1.46h2.25v7.992c0 .245.03.432.093.563.068.13.167.221.297.273.13.047.294.07.492.07.141 0 .266-.005.375-.015.115-.016.211-.031.29-.047l.007 1.656a4.231 4.231 0 0 1-.625.149 4.643 4.643 0 0 1-.742.054c-.495 0-.927-.08-1.297-.242a1.786 1.786 0 0 1-.843-.797c-.198-.364-.297-.844-.297-1.437V1.46ZM89.058 11.047l2.242-7.5h2.414l-3.398 9.726a5.264 5.264 0 0 1-.297.688c-.12.245-.284.476-.492.695a2.302 2.302 0 0 1-.774.547c-.307.14-.685.211-1.133.211-.213 0-.388-.013-.523-.039a9.164 9.164 0 0 1-.484-.11v-1.648h.18c.062.005.122.008.179.008.297 0 .539-.034.726-.102.188-.067.339-.171.454-.312a1.5 1.5 0 0 0 .273-.531l.633-1.633Zm-.938-7.5 1.836 6.125.32 2.383-1.53.164-3.04-8.672h2.414Z",fill:o})})},Y={color:"info.main",fontSize:44},X=[{value:"person",icon:(0,c.jsx)(q,{sx:Y}),label:(0,l.__)("Accessibility Person Icon","pojo-accessibility")},{value:"eye",icon:(0,c.jsx)(U,{sx:Y}),label:(0,l.__)("Accessibility Eye Icon","pojo-accessibility")},{value:"controls",icon:(0,c.jsx)(K,{sx:Y}),label:(0,l.__)("Accessibility Controls Slider Icon","pojo-accessibility")},{value:"text",icon:(0,c.jsx)(Q,{sx:Y}),label:(0,l.__)("Accessibility Text Badge Icon","pojo-accessibility")},{value:"custom",icon:(0,c.jsx)(Q,{sx:Y}),label:(0,l.__)("Accessibility Custom Icon","pojo-accessibility")}],J=X,ee=e=>Object.values(X).find(i=>i.value===e);var ie=t(18970);2207==t.j&&(m=t(50602));var te=t(86087),oe=t(44302),se=t(47143);const ne=e=>{const{fallback:i=null,children:t}=e,{checkingPermissions:o,hasUploadPermissions:s}=(0,se.useSelect)(e=>{const i=e("core");return{hasUploadPermissions:i.canUser("read","media"),checkingPermissions:!i.hasFinishedResolution("canUser",["read","media"])}});return(0,c.jsxs)(c.Fragment,{children:[o&&(0,c.jsx)(oe.A,{}),!o&&s?t:i]})},ae=()=>{const[e,i]=(0,te.useState)(!1),{iconDesign:t,updateIconDesign:o}=(0,n.vS)(),{unfilteredUploads:s}=(0,D.F)(),{save:a}=(0,n.rX)(),[d,p]=(0,te.useState)((0,l.__)("Add custom icon","pojo-accessibility"));(0,te.useEffect)(()=>{t?.custom?.url&&p((0,l.__)("Change custom icon","pojo-accessibility"))},[]);return(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(ne,{children:(0,c.jsx)(m.A,{color:"info",onClick:()=>{t?.custom?.url?r.K.sendEvent(r.m.updateCustomIconClicked):r.K.sendEvent(r.m.addCustomIconClicked),s?(()=>{const e=window.wp.media({title:(0,l.__)("Select SVG Icon","pojo-accessibility"),library:{type:"image/svg+xml"},button:{text:(0,l.__)("Use this SVG","pojo-accessibility")},multiple:!1});e.on("select",async()=>{const i=e.state().get("selection").first().toJSON();await(async e=>{const i=await I.A.getMedia(e.url),s={icon:"custom",custom:{...t.custom,id:e.id,url:e.url,mime:e.mime,svg:i}};o(s),t?.custom?.url?r.K.sendEvent(r.m.customIconUpdated):r.K.sendEvent(r.m.customIconAdded)})(i)}),e.uploader.options.uploader.params={upload_source:"ea11y-custom-icon"},e.open()})():i(!0)},startIcon:(0,c.jsx)(ie.A,{}),children:d})}),(0,c.jsx)(Gi.A,{open:e,title:(0,l.__)("Allow SVG upload","pojo-accessibility"),approveText:(0,l.__)("Allow SVG upload","pojo-accessibility"),onApprove:async()=>{await a({ea11y_unfiltered_files_upload:!0}),i(!1),window.location.reload()},onCancel:()=>i(!1),children:(0,l.__)("To upload SVG files, you need to enable unfiltered uploads.","pojo-accessibility")})]})},re=e=>{const{iconDesign:i,updateIconDesign:t}=(0,n.vS)(),o=e=>()=>{t({icon:e}),r.K.sendEvent(r.m.iconTypeSelected,{iconType:e})};return(0,c.jsxs)(F.A,{children:[(0,c.jsxs)(ce,{id:"icon-select-radio-buttons-group-label",color:"secondary",children:[(0,c.jsx)(A.A,{variant:"subtitle2",marginBottom:1,children:(0,l.__)("Icon","pojo-accessibility")}),(0,c.jsx)(ae,{})]}),(0,c.jsx)(le,{...e,"aria-labelledby":"icon-select-radio-buttons-group-label",name:"icon-select-radio-buttons-group",value:i.icon,children:J.map(e=>(0,c.jsx)(Xi,{iconType:"select",option:e,clickHandler:o},e.value))})]})},le=(0,s.I)(W.A)`
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: space-between;
	gap: ${({theme:e})=>e.spacing(2)};
`,ce=(0,s.I)(Z.A)`
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	gap: ${({theme:e})=>e.spacing(2)};
	align-items: center;
	justify-content: space-between;
`;var de=t(51137);2207==t.j&&(y=t(92272));var pe=t(62017),he=t(75260),xe=t(55016),ue=t(60765);const ge=["PX"],me=[{value:0,label:"0"},{value:32,label:"32"}],be=()=>{const{iconDesign:e,updateIconDesign:i}=(0,n.vS)(),[t,o]=(0,te.useState)(0),[s,a]=(0,te.useState)(!0);(0,te.useEffect)(()=>{(e?.cornerRadius?.radius||0===e?.cornerRadius?.radius)&&window?.ea11yWidget?.iconSettings?.style?.cornerRadius||i({cornerRadius:{radius:"text"===e?.icon?4:32,unit:ge[t].toLowerCase()}})},[e?.icon]),(0,te.useEffect)(()=>{const o=e?.cornerRadius?.radius;isNaN(o)&&(a(!1),i({cornerRadius:{radius:0,unit:ge[t].toLowerCase()}})),(o>32||o<0)&&(a(!1),o>32?i({cornerRadius:{radius:32,unit:ge[t].toLowerCase()}}):o<0&&i({cornerRadius:{radius:0,unit:ge[t].toLowerCase()}})),o>=0&&o<=32&&a(!0)},[e?.cornerRadius?.radius]);const d=(0,xe.A)({variant:"popover",popupId:"textfield-inner-selection"}),p=(e,o,s)=>{i({cornerRadius:{radius:parseInt(e.target.value),unit:ge[t].toLowerCase()}}),r.K.sendEvent(r.m.radiusChanged,{previous_radius_value:parseInt(s),new_radius_value:parseInt(e.target.value),interaction_type:o})};return(0,c.jsxs)(F.A,{id:"icon-radius-controls-group","aria-label":(0,l.__)("Widget icon radius control","pojo-accessibility"),"aria-describedby":(0,l.__)("Set corner radius for the icon from 0px to 32px using input or slider. Default corner radius value for icon is 32px and for text icon it is 8px.","pojo-accessibility"),"aria-labelledby":"icon-radius-controls-group-label",children:[(0,c.jsx)(Z.A,{id:"icon-radius-controls-group-label",color:"secondary","aria-label":(0,l.__)("Widget icon radius control","pojo-accessibility"),children:(0,c.jsx)(A.A,{variant:"subtitle2",marginBottom:1,children:(0,l.__)("Corner radius","pojo-accessibility")})}),(0,c.jsxs)(ye,{children:[(0,c.jsx)(je,{color:"info",name:(0,l.__)("icon radius input field","pojo-accessibility"),error:!s,helperText:s?"":"Max: 32PX",inputProps:{"aria-label":(0,l.sprintf)(
// Translators: %s - units
// Translators: %s - units
(0,l.__)("Numbered input for corner radius in %s.","pojo-accessibility"),ge[t]),"aria-describedby":(0,l.__)("Set corner radius for the icon from 0px to 32px.","pojo-accessibility"),role:"textbox","aria-labelledby":"icon-radius-controls-group-label"},InputProps:{endAdornment:(0,c.jsxs)(de.A,{position:"end",children:[(0,c.jsx)(m.A,{size:"small",color:"inherit",sx:{font:"inherit",minWidth:"initial",width:"15px",fontSize:"14px"},...(0,ue.cO)(d),disabled:!0,"aria-label":(0,l.__)("Corner radius unit","pojo-accessibility"),"aria-describedby":(0,l.__)("Select corner radius unit in pixels.","pojo-accessibility"),role:"button",children:ge[t]}),(0,c.jsx)(y.A,{MenuListProps:{dense:!0},...(0,ue.NA)(d),"aria-label":(0,l.__)("Select corner radius unit","pojo-accessibility"),role:"menu","aria-disabled":"true","aria-describedby":(0,l.__)("Corner radius unit is pixels.","pojo-accessibility"),children:ge.map((e,i)=>(0,c.jsx)(f.A,{onClick:()=>(e=>{o(e),d.close()})(i),children:e},e))})]})},onChange:i=>p(i,"input",e?.cornerRadius?.radius),value:e?.cornerRadius?.radius||0}),(0,c.jsx)(pe.A,{color:"info",onChange:i=>p(i,"slider",e?.cornerRadius?.radius),value:e?.cornerRadius?.radius||0,min:0,max:32,marks:me,name:(0,l.__)("icon radius slider","pojo-accessibility"),"aria-label":(0,l.__)("Slider for corner radius","pojo-accessibility"),"aria-describedby":(0,l.__)("Set corner radius for the icon from 0px to 32px.","pojo-accessibility"),role:"slider","aria-valuemin":0,"aria-valuemax":32})]})]})},je=(0,s.I)(he.A)`
	width: 105px;
	.wp-admin & .MuiInputBase-input,
	& .MuiInputBase-input:focus {
		background-color: initial;
		box-shadow: none;
		border: 0;
		color: inherit;
		outline: 0;
		padding: 10px 10px 10px 10px;
		&.MuiInputBase-inputSizeSmall {
			padding: 8.5px 8px 8.5px 8px;
		}
		height: 60px;
	}
`,ye=(0,s.I)(g.A)`
	display: flex;
	flex-direction: row;
	align-items: center;
	gap: ${({theme:e})=>e.spacing(4)};
`;var fe=t(5322),ve=t(7612),_e=t(81591);const Ae=(0,s.I)(ve.HC)`
	width: 100%;
	border: 1px solid rgb(0 0 0 / 0.12);
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	padding-left: 10px;
`,Ce=()=>{const{iconDesign:e,updateIconDesign:i}=(0,n.vS)(),t=(0,_e.YQ)(e=>{r.K.sendEvent(r.m.colorChanged,{color:e})},1e3);return(0,c.jsxs)(F.A,{fullWidth:!0,children:[(0,c.jsx)(Z.A,{id:"color-picker-label",color:"secondary",children:(0,c.jsx)(A.A,{variant:"subtitle2",marginBottom:1,color:"text.primary",children:(0,l.__)("Color","pojo-accessibility")})}),(0,c.jsxs)(fe.A,{padding:1,border:1,borderColor:"divider",borderRadius:1,children:[(0,c.jsx)(ve.jI,{color:e.color,onChange:e=>{i({color:e}),t(e)},className:"widget-settings-color-picker"}),(0,c.jsxs)(fe.A,{marginTop:2,display:"flex",children:[(0,c.jsx)(g.A,{padding:2,sx:{backgroundColor:e.color},borderRadius:1,marginRight:1}),(0,c.jsx)(Ae,{"aria-label":(0,l.__)("HEX color code","pojo-accessibility"),"aria-description":(0,l.__)("Provide the HEX code here without a leading hash sign","pojo-accessibility"),color:e.color,onChange:e=>{i({color:e}),t(e)}})]})]})]})};var we=t(93248),ke=t(94284),Ie=t(55984);2207==t.j&&(_=t(53955));const De=({mode:e})=>{const{iconPosition:i,updateIconPosition:t}=(0,n.Aw)(),o=[{value:"top-left",label:(0,l.__)("Top Left","pojo-accessibility")},..."desktop"===e?[{value:"top-center",label:(0,l.__)("Top Center","pojo-accessibility")}]:[],{value:"top-right",label:(0,l.__)("Top Right","pojo-accessibility")},{value:"center-left",label:(0,l.__)("Center Left","pojo-accessibility")},..."desktop"===e?[{value:"empty"}]:[],{value:"center-right",label:(0,l.__)("Center Right","pojo-accessibility")},{value:"bottom-left",label:(0,l.__)("Bottom Left","pojo-accessibility")},..."desktop"===e?[{value:"bottom-center",label:(0,l.__)("Bottom Center","pojo-accessibility")}]:[],{value:"bottom-right",label:(0,l.__)("Bottom Right","pojo-accessibility")}];return(0,c.jsxs)(F.A,{children:[(0,c.jsx)(Z.A,{id:"alignment-matrix-control",color:"secondary",children:(0,c.jsx)(A.A,{variant:"subtitle2",marginBottom:3,color:"text.primary",children:(0,l.__)("Default Position","pojo-accessibility")})}),(0,c.jsx)(ke.A,{color:"info",elevation:0,children:(0,c.jsx)(g.A,{display:"flex",justifyContent:"center",padding:4,width:"100%",children:(0,c.jsx)(ke.A,{color:"secondary",children:(0,c.jsx)(W.A,{dir:"ltr","aria-labelledby":"alignment-matrix-control",value:i[e].position,onChange:i=>{t(e,"position",i.target.value),r.K.sendEvent(r.m.positionButtonClicked,{buttonData:{mode:e,value:i.target.value}})},name:"alignment-matrix-control",sx:{display:"grid",gridTemplateColumns:"desktop"===e?"repeat(3, 1fr)":"repeat(2, 1fr)",gap:1,columnGap:"desktop"===e?6:1,alignItems:"center",borderWidth:5,borderStyle:"solid",borderColor:"secondary.main",borderRadius:1,minWidth:"desktop"===e?"auto":"100px"},children:o.map(e=>"empty"===e.value?(0,c.jsx)(g.A,{},e.value):(0,c.jsx)(_.A,{title:e.label,children:(0,c.jsx)(we.A,{sx:{justifyContent:"center",margin:0},value:e.value,control:(0,c.jsx)(Ie.A,{color:"secondary"})})},e.value))})})})})]})};var Se=t(8546);const Ee=["PX","REM","EM"],Me=[{value:"left",label:(0,l.__)("To the left","pojo-accessibility")},{value:"right",label:(0,l.__)("To the right","pojo-accessibility")}],Pe=[{value:"top",label:(0,l.__)("Higher","pojo-accessibility")},{value:"bottom",label:(0,l.__)("Lower","pojo-accessibility")}],$e=(0,s.I)(g.A,{shouldForwardProp:e=>"isError"!==e})`
	display: flex;
	gap: ${({theme:e})=>e.spacing(1)};
	margin-top: ${({theme:e})=>e.spacing(2)};
	margin-bottom: ${({theme:e,isError:i})=>i?e.spacing(4):"initial"};

	transition: all 100ms ease-in-out;
`,Re=(0,s.I)(he.A)`
	width: 200px;
	height: 56px;

	.wp-admin & .MuiInputBase-input,
	& .MuiInputBase-input:focus {
		height: 56px;
		background-color: initial;
		box-shadow: none;
		border: 0;
		color: inherit;
		outline: 0;
		padding: 16.5px 14px 16.5px 14px;

		&.MuiInputBase-inputSizeSmall {
			padding: 8.5px 14px 8.5px 14px;
		}
	}
`,ze=({type:e,disabled:i,mode:t})=>{const{iconPosition:o,updateExactPosition:s}=(0,n.Aw)(),[a,d]=(0,te.useState)(0),[p,h]=(0,te.useState)(o[t]?.exactPosition[e]?.value),[x,u]=(0,te.useState)(p>=5&&p<=550),g=(0,xe.A)({variant:"popover",popupId:"position-settings"});return(0,c.jsxs)($e,{isError:!x,role:"group","aria-label":(0,l.sprintf)(
// Translators: %1$s - date, %2$s - time
// Translators: %1$s - date, %2$s - time
(0,l.__)("%1$s icon %2$s settings","pojo-accessibility"),t,e),children:[(0,c.jsx)(Re,{size:"medium",color:"info",error:!x,helperText:x?"":"Invalid value",disabled:i,value:p,onChange:i=>{const n=parseInt(i.target.value,10)||0,a=n>=5&&n<=550;h(i.target.value),u(a),a&&(s(t,e,o[t]?.exactPosition[e]?.direction,n,o[t]?.exactPosition[e]?.unit),r.K.sendEvent(r.m.handleValueChanged,{positionData:{mode:t,type:e,value:n,unit:o[t]?.exactPosition[e]?.unit,direction:o[t]?.exactPosition[e]?.value}}))},inputProps:{"aria-label":(0,l.sprintf)(
// Translators: %s - units
// Translators: %s - units
(0,l.__)("Number of %s","pojo-accessibility"),Ee[a]),"aria-describedby":`ea11y-${t}-position-settings`},InputProps:{endAdornment:(0,c.jsxs)(de.A,{position:"end",children:[(0,c.jsx)(m.A,{size:"small",color:"inherit",sx:{font:"inherit",minWidth:"initial"},...(0,ue.cO)(g),disabled:i,children:Ee[a]}),(0,c.jsx)(y.A,{MenuListProps:{dense:!0},...(0,ue.NA)(g),children:Ee.map((i,n)=>(0,c.jsx)(f.A,{onClick:()=>(i=>{d(i),s(t,e,o[t]?.exactPosition[e]?.direction,o[t]?.exactPosition[e]?.value,Ee[i]),g.close(),r.K.sendEvent(r.m.handleUnitChanged,{positionData:{mode:t,type:e,unit:Ee[i],value:o[t]?.exactPosition[e]?.direction,direction:o[t]?.exactPosition[e]?.value}})})(n),children:i},i))})]})}}),(0,c.jsx)(Se.A,{fullWidth:!0,color:"info",name:(0,l.__)("Direction","pojo-accessibility"),variant:"outlined",onChange:i=>{s(t,e,i.target.value,o[t]?.exactPosition[e]?.value,o[t]?.exactPosition[e]?.unit),r.K.sendEvent(r.m.handleDirectionChanged,{positionData:{mode:t,type:e,value:o[t]?.exactPosition[e]?.value,unit:o[t]?.exactPosition[e]?.unit,direction:i.target.value}})},disabled:i,value:o[t]?.exactPosition?.[e].direction,MenuProps:{MenuListProps:{sx:{minWidth:150}},anchorOrigin:{vertical:"bottom",horizontal:"left"},transformOrigin:{vertical:"top",horizontal:"left"}},children:"horizontal"===e?Me.map(e=>(0,c.jsx)(f.A,{value:e.value,children:e.label},e.value)):Pe.map(e=>(0,c.jsx)(f.A,{value:e.value,children:e.label},e.value))})]})};var Te=t(96617),Be=t(96989);const He=()=>{const{selectedMenu:e,widgetMenuSettings:i,skipToContentSettings:t,widgetActivationSettings:o,iconDesign:s,iconPosition:a,hasChanges:d,hasError:p,setHasChanges:h}=(0,n.t0)(),{save:x}=(0,n.rX)(),{success:u,error:g}=(0,w.m)();return(0,c.jsx)(Le,{children:(0,c.jsx)(Be.A,{variant:"contained",color:"primary",onClick:async()=>{let n={};"capabilities"===e.child?n={ea11y_widget_menu_settings:i,ea11y_skip_to_content_settings:t,ea11y_widget_activation_settings:o}:"design"===e.child&&(n={ea11y_widget_icon_settings:{style:s,position:a}});try{await x(n),u((0,l.__)("Settings saved!","pojo-accessibility")),h(!1),r.K.sendEvent(r.m.saveButtonClicked,{savedData:n})}catch(e){g((0,l.__)("Failed to save settings!","pojo-accessibility"))}},disabled:!d||Object.keys(p).some(e=>p[e]),children:(0,l.__)("Save changes","pojo-accessibility")})})},Le=(0,s.I)(g.A)`
	width: 100%;
	display: flex;
	justify-content: flex-end;

	padding: ${({theme:e})=>e.spacing(2)};
	border-top: 1px solid ${({theme:e})=>e.palette.divider};
`;var Oe=t(41226),Ve=t(47428),Fe=t(71974),Ze=t(40976),We=t(84054),Ne=t(62784),Ge=t(63364);const Ke=`\n\x3c!-- wp:heading {"textAlign":"center","level":6} --\x3e\n<h6 class="wp-block-heading has-text-align-center">${(0,l.sprintf)((0,l.__)("Accessibility Statement for %s","pojo-accessibility"),"{company_website}")}</h6>\n\x3c!-- /wp:heading --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.sprintf)((0,l.__)("%s is committed to ensuring digital accessibility for people with disabilities. We are continually improving the user experience for everyone and applying the relevant accessibility standards.","pojo-accessibility"),"<strong>{company_name}</strong>")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:heading {"level":6} --\x3e\n<h6>${(0,l.__)("Conformance status","pojo-accessibility")}</h6>\n\x3c!-- /wp:heading --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.__)("The Web Content Accessibility Guidelines (WCAG) defines requirements for designers and developers to improve accessibility for people with disabilities. It defines three levels of conformance: Level A, Level AA, and Level AAA.","pojo-accessibility")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.sprintf)((0,l.__)("%s is making constant efforts to improve the accessibility of its site and services in the belief that it is our collective moral obligation to allow seamless, accessible, and unhindered use for those of us with disabilities.","pojo-accessibility"),"<strong>{company_name}</strong>")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.sprintf)((0,l.__)("We aim to make all pages and content on %s accessible, but some content may not yet fully meet the highest accessibility standards. This could be due to challenges in identifying the most suitable technological solution.","pojo-accessibility"),"<strong>{company_website}</strong>")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.__)("We may revise this Statement periodically to reflect improvements or changes to our accessibility practices.","pojo-accessibility")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:heading {"level":6} --\x3e\n<h6>${(0,l.__)("Feedback","pojo-accessibility")}</h6>\n\x3c!-- /wp:heading --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.sprintf)((0,l.__)("We welcome your feedback on the accessibility of %s website. Please let us know if you encounter accessibility barriers on our website:","pojo-accessibility"),"<strong>{company_name}</strong>")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.sprintf)((0,l.__)("E-mail: %s","pojo-accessibility"),"<strong>{company_email}</strong>")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.__)("We try to respond to feedback within 3–5 business days.","pojo-accessibility")}</p>\n\x3c!-- /wp:paragraph --\x3e\n\x3c!-- wp:paragraph --\x3e\n<p>${(0,l.sprintf)((0,l.__)("This statement was created on %s.","pojo-accessibility"),"{current_date}")} </p>\n\x3c!-- /wp:paragraph --\x3e`,Ue=(0,s.I)(he.A)`
	width: 100%;

	.wp-admin & .MuiInputBase-input,
	& .MuiInputBase-input:focus {
		background-color: initial;
		box-shadow: none;
		border: 0;
		color: inherit;
		outline: 0;
		padding: 16.5px 14px 16.5px 14px;
		&.MuiInputBase-inputSizeSmall {
			padding: 8.5px 14px 8.5px 14px;
		}
		height: 40px;
	}
`,qe=({open:e,close:i})=>{const[t,o]=(0,te.useState)(!0),[s,a]=(0,te.useState)(!0),[d,p]=(0,te.useState)(!0),{success:h,error:x}=(0,w.m)(),{companyData:u,setCompanyData:m,setAccessibilityStatementData:b,setShowAccessibilityGeneratedInfotip:j}=(0,n.t0)(),{save:y}=(0,n.rX)(),f=u.company_name&&u.company_website&&u.company_email&&s&&t&&d,v=()=>{i()},_=(e,i)=>{const t={...u,[e]:i};m(t),((e,i)=>{switch(e){case"company_website":p(/^(https?:\/\/)?([a-zA-Z0-9\u00A1-\uFFFF-]+\.)+[a-zA-Z\u00A1-\uFFFF]{2,}\/?$/.test(i));break;case"company_name":o("string"==typeof(t=i)&&t.trim().length>0);break;case"company_email":a(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(i))}var t})(e,i)};return(0,c.jsx)(Ne.A,{fallback:(0,c.jsx)(Xe,{}),children:(0,c.jsxs)(Oe.A,{open:e,onClose:v,"aria-labelledby":"alert-dialog-title","aria-describedby":"alert-dialog-description",fullWidth:!0,maxWidth:"lg",sx:{zIndex:99999},children:[(0,c.jsx)(Ze.A,{logo:!1,onClose:v,children:(0,c.jsx)(We.A,{children:(0,l.__)("Statement generator","pojo-accessibility")})}),(0,c.jsx)(Fe.A,{dividers:!0,children:(0,c.jsxs)(g.A,{display:"grid",gridTemplateColumns:"repeat(2, 1fr)",gap:8,children:[(0,c.jsxs)(g.A,{children:[(0,c.jsx)(A.A,{variant:"subtitle1",color:"text.primary",children:(0,l.__)("Enter your company info","pojo-accessibility")}),(0,c.jsx)(A.A,{variant:"body2",color:"text.secondary",marginBottom:3,children:(0,l.__)("This will generate an accessibility statement that you can preview on the right.","pojo-accessibility")}),(0,c.jsxs)(F.A,{fullWidth:!0,children:[(0,c.jsx)(Z.A,{htmlFor:"ea11y-statement-generator-company-name",children:(0,l.__)("Company name","pojo-accessibility")}),(0,c.jsx)(Ue,{type:"text",id:"ea11y-statement-generator-company-name",variant:"outlined",size:"small",color:"secondary",margin:"normal",value:u.company_name,placeholder:"Acme Inc.",onChange:e=>_("company_name",e.currentTarget.value),error:!1===t}),!t&&(0,c.jsx)(Ge.A,{children:(0,l.__)("Company name cannot be empty","pojo-accessibility")})]}),(0,c.jsxs)(F.A,{fullWidth:!0,children:[(0,c.jsx)(Z.A,{htmlFor:"ea11y-statement-generator-company-website",children:(0,l.__)("Company website","pojo-accessibility")}),(0,c.jsx)(Ue,{type:"text",id:"ea11y-statement-generator-company-website",variant:"outlined",size:"small",color:"secondary",margin:"normal",value:u.company_website,placeholder:"https://www.acme.com/",onChange:e=>_("company_website",e.currentTarget.value),error:!1===d}),!d&&(0,c.jsx)(Ge.A,{children:(0,l.__)("Please enter a valid domain","pojo-accessibility")})]}),(0,c.jsxs)(F.A,{fullWidth:!0,children:[(0,c.jsx)(Z.A,{htmlFor:"ea11y-statement-generator-business-website",children:(0,l.__)("Business email","pojo-accessibility")}),(0,c.jsx)(Ue,{type:"text",id:"ea11y-statement-generator-business-website",variant:"outlined",size:"small",color:"secondary",margin:"normal",value:u.company_email,placeholder:"contact@acme.com",onChange:e=>_("company_email",e.currentTarget.value),error:!1===s}),!s&&(0,c.jsx)(Ge.A,{children:(0,l.__)("Please enter a valid email","pojo-accessibility")})]})]}),(0,c.jsxs)(g.A,{children:[(0,c.jsx)(A.A,{variant:"subtitle1",color:"text.primary",marginBottom:6,children:(0,l.__)("Preview your accessibility statement","pojo-accessibility")}),(0,c.jsx)(g.A,{border:1,borderColor:"divider",borderRadius:1,padding:2,children:(0,c.jsx)(ei,{htmlString:Ke,replacements:u})})]})]})}),(0,c.jsxs)(Ve.A,{children:[(0,c.jsx)(Be.A,{onClick:v,color:"secondary",children:(0,l.__)("Cancel","pojo-accessibility")}),(0,c.jsx)(Be.A,{onClick:async()=>{const e=((e,i)=>{let t=e;return Object.keys(i).forEach(e=>{const o=`{${e}}`,s=i[e];t=t.replace(new RegExp(o,"g"),s)}),t})(Ke,u);try{const t=await I.A.addPage({title:"Accessibility statement",content:e,status:"publish"});await b({statement:e,pageId:t.id,id:t.id,label:"Accessibility statement",createdOn:t.date,link:t.link}),await j(!0),await y({ea11y_accessibility_statement_data:{statement:e,pageId:t.id,id:t.id,createdOn:t.date,link:t.link,label:"Accessibility statement"},ea11y_show_accessibility_generated_page_infotip:!0}),window?.ea11yWidget&&(window.ea11yWidget.accessibilityStatementURL=t.link),await i(),await h("Page created","pojo-accessibility"),r.K.sendEvent(r.m.statementPageCreated)}catch(e){x("Error while creating page","pojo-accessibility"),console.error(e)}},variant:"contained",color:"primary",disabled:!f,children:(0,l.__)("Create statement & page","pojo-accessibility")})]})]})})};var Qe=t(95408),Ye=t(12332);const Xe=()=>(0,c.jsx)(Qe.Ay,{severity:"error",children:(0,c.jsx)(Ye.A,{children:(0,l.__)("Something went wrong!","pojo-accessibility")})});var Je=t(10900);const ei=({htmlString:e,replacements:i})=>{const t=e=>{if("tag"===e.type){const i=e.name;if(["h1","h2","h3","h4","h5","h6","p","span","div","strong"].includes(i))return(0,c.jsx)(A.A,{variant:"p"===i?"body2":"subtitle2",component:i,marginBottom:1,sx:e.attribs.class?{textAlign:"center"}:{},color:"p"===i?"text.secondary":"text.primary",children:e.children&&e.children.map(e=>t(e))})}return"text"===e.type?(e=>{let t=e;return Object.keys(i).forEach(e=>{const o=`{${e}}`,s=i[e];if(""===i[e]){const i=`[${e?.split("_").join(" ")}]`;t=t.replace(new RegExp(o,"g"),i)}t=t.replace(new RegExp(o,"g"),s)}),t})(e.data):null};return(0,c.jsx)(c.Fragment,{children:(0,Je.Ay)(e,{replace:t})})};var ii=t(43331);const ti=({src:e,onLoad:i,onError:t})=>{const{planData:o}=(0,n.t0)();return(0,te.useEffect)(()=>{if(document.querySelector(`script[src="${e}"]`))return void console.log(`Script with src "${e}" already loaded.`);const s=document.createElement("script");return s.src=e||`${window?.ea11ySettingsData?.widgetUrl}?api_key=${o?.public_api_key}`,s.async=!0,s.referrerPolicy="origin",s.onload=()=>{console.log(`Script loaded successfully: ${s.src}`),i?i():console.log("External script loaded!")},s.onerror=()=>{console.error(`Failed to load script: ${s.src}`),t?t():console.error("Failed to load the external script.")},document.head.appendChild(s),()=>{document.getElementById(ii.no)&&document.getElementById(ii.no).remove(),document.head.removeChild(s),console.log(`Script removed: ${s.src}`)}},[e,i,t]),null};var oi=t(97579),si=t(40730),ni=t(49775),ai=t(98053);const ri=(0,s.I)(si.A)`
	padding: ${({theme:e})=>e.spacing(2)};
	margin-block: ${({theme:e})=>e.spacing(4)};
	margin-inline: auto;
	max-width: 1200px;
`,li=(0,s.I)(g.A)`
	display: flex;
	align-items: center;
	justify-content: space-between;
`,ci=(0,s.I)(ai.A)`
	input {
		height: 56px !important;
	}
`,di=(0,s.I)(A.A)`
	display: flex;
	align-items: center;
	gap: 8px;
`,pi=()=>{var e;const{widgetActivationSettings:i,setWidgetActivationSettings:t,setHasChanges:o}=(0,n.t0)();return(0,c.jsxs)(ri,{variant:"outlined",children:[(0,c.jsxs)(li,{children:[(0,c.jsxs)(di,{variant:"subtitle1",id:"ea11y-widget-activation-toggle",children:[(0,l.__)("Widget Activation","pojo-accessibility"),(0,c.jsx)(ni.A,{tabIndex:"0",content:(0,c.jsxs)(g.A,{sx:{padding:2,maxWidth:"250px"},children:[(0,c.jsx)(A.A,{variant:"subtitle2",sx:{marginBlockEnd:1},children:(0,l.__)("Widget Activation","pojo-accessibility")}),(0,c.jsx)(A.A,{variant:"body2",children:(0,l.__)("Disabling it will prevent the widget from loading entirely.","pojo-accessibility")})]}),placement:"right",arrow:!0,children:(0,c.jsx)(oi.A,{fontSize:"small"})})]}),(0,c.jsx)(ci,{size:"medium",color:"info",checked:null===(e=i.enabled)||void 0===e||e,onChange:()=>{const e=!i.enabled;t({...i,enabled:e}),o(!0),r.K.sendEvent(r.m.toggleClicked,{state:e?"on":"off",type:"Widget activation"})},inputProps:{"aria-labelledby":"ea11y-widget-activation-toggle"}})]}),(0,c.jsx)(A.A,{variant:"body1",children:(0,l.__)("Enable or disable the accessibility widget on your website.","pojo-accessibility")})]})};var hi=t(21627),xi=t(73916),ui=t(99054),gi=t.n(ui);const mi=({content:e})=>{const[i,t]=(0,te.useState)(!1),[o,s]=(0,te.useState)(!1);return(0,c.jsx)(_.A,{open:i,onClose:()=>{t(!1),setTimeout(()=>s(!1),200)},onOpen:()=>{t(!0)},placement:"top",title:o?(0,l.__)("Copied!","pojo-accessibility"):(0,l.__)("Copy link","pojo-accessibility"),arrow:!0,PopperProps:{modifiers:[{name:"offset",options:{offset:[0,-16]}},{name:"zIndex",enabled:!0,phase:"beforeWrite",fn:({state:e})=>{e.styles.popper.zIndex="99999"}}]},children:(0,c.jsx)(xi.A,{onClick:async()=>{await gi()(e),s(!0)},sx:{marginLeft:1},children:(0,c.jsx)(hi.A,{width:"1em",height:"1em"})})})};var bi=t(59471),ji=t(93832);2207==t.j&&(E=t(74565));const yi=()=>{const{accessibilityStatementData:e}=(0,n.t0)();return(0,c.jsx)(_.A,{placement:"top",title:(0,l.__)("Edit page","pojo-accessibility"),arrow:!0,PopperProps:{modifiers:[{name:"offset",options:{offset:[0,-16]}},{name:"zIndex",enabled:!0,phase:"beforeWrite",fn:({state:e})=>{e.styles.popper.zIndex="99999"}}]},children:(0,c.jsx)(xi.A,{onClick:()=>{const i=window?.ea11ySettingsData?.adminUrl,t=(0,ji.addQueryArgs)(`${i}post.php`,{post:e?.pageId,action:"edit"});(0,E.Fe)(t)},sx:{marginLeft:1},"aria-label":"Edit accessibility statement page",children:(0,c.jsx)(bi.A,{})})})};var fi=t(86853),vi=t(86269),_i=t(2660);const Ai=()=>{const{setShowAccessibilityGeneratedInfotip:e}=(0,n.t0)(),{save:i}=(0,n.rX)();return(0,c.jsxs)(si.A,{elevation:0,sx:{maxWidth:300},children:[(0,c.jsx)(_i.A,{title:""}),(0,c.jsx)(vi.A,{children:(0,c.jsx)(A.A,{variant:"body2",color:"text.secondary",children:(0,l.__)("We went ahead and linked the accessibility statement page you just created to your widget.","pojo-accessibility")})}),(0,c.jsx)(fi.A,{children:(0,c.jsx)(m.A,{size:"small",variant:"contained",color:"info",onClick:async()=>{e(!1),await i({ea11y_show_accessibility_generated_page_infotip:!1})},children:(0,l.__)("Got it","pojo-accessibility")})})]})},Ci=(0,s.I)(g.A)`
	display: grid;
	justify-content: space-evenly;

	gap: ${({theme:e})=>e.spacing(5)};
	padding: ${({theme:e})=>e.spacing(2)};

	grid-template-columns: repeat(2, 1fr);
	${({theme:e})=>e.breakpoints.down("lg")} {
		grid-template-columns: repeat(1, 1fr);
	}
`,wi=({children:e})=>(0,c.jsx)(Ci,{children:e});var ki=t(57936),Ii=t(46682),Di=t(73452),Si=t(43307),Ei=t(81272),Mi=t(6603),Pi=t(11657),$i=t(2976);const Ri=(0,s.I)(Ei.A)`
	border: none;
	width: 100%;
`,zi=(0,s.I)(Pi.A)`
	padding: 0;
	min-height: auto;
	justify-content: left;
	&.Mui-expanded {
		min-height: auto;
	}

	& .MuiAccordionSummary-content,
	& .MuiAccordionSummary-content.Mui-expanded {
		margin: 0 !important;
		align-items: center;
		flex-grow: 0;
	}
`,Ti=(0,s.I)(g.A)`
	display: flex;
	gap: 16px;
	align-items: flex-start;
`,Bi=(0,s.I)(Z.A)`
	display: flex;
	align-items: center;
	gap: 8px;
	white-space: nowrap;
	padding-top: 7px;
`,Hi=(0,s.I)(he.A)`
	width: 100%;
	input {
		height: 36px;
	}
`,Li=(0,s.I)(Mi.A)`
	padding: 5px 45px 5px 0;
`,Oi=({sitemap:e})=>{const{widgetMenuSettings:i,setWidgetMenuSettings:t,hasChanges:o,setHasChanges:s,hasError:a,setHasError:d}=(0,n.t0)();return(0,c.jsxs)(Ri,{children:[(0,c.jsxs)(zi,{"aria-controls":"panel-content",id:"panel-header",children:[(0,c.jsx)($i.A,{sx:{padding:0},children:e?.icon}),(0,c.jsx)(Si.A,{primary:e?.title,sx:{flexGrow:0,marginRight:1},id:"ea11y-sitemap-toggle"})]}),(0,c.jsx)(Li,{children:(0,c.jsxs)(Ti,{children:[(0,c.jsxs)(Bi,{htmlFor:"sitemap-url",children:[(0,l.__)("Sitemap URL","pojo-accessibility"),(0,c.jsx)(ni.A,{content:(0,c.jsx)(A.A,{variant:"body2",sx:{p:2},children:(0,l.__)("You need to add a link to activate this","pojo-accessibility")}),placement:"right",arrow:!0,children:(0,c.jsx)(oi.A,{fontSize:"small"})})]}),(0,c.jsxs)(g.A,{sx:{flexGrow:1},children:[(0,c.jsx)(Hi,{id:"sitemap-url",type:"url",onChange:e=>{t({...i,sitemap:{enabled:!0,url:e.target.value}});const o=(0,E.wv)(e.target.value);d({...a,sitemap:!o}),s(o)},onBlur:()=>{o&&r.K.sendEvent(r.m.fieldContentUpdated,{fieldName:"sitemap-url",value:i.sitemap?.url})},value:i.sitemap?.url,error:a.sitemap,size:"small",variant:"outlined"}),a.sitemap&&(0,c.jsx)(A.A,{variant:"caption",color:"error",children:(0,l.__)("Please enter valid URL!","pojo-accessibility")})]})]})})]})},Vi=({childKey:e,childValue:i,parentKey:t,disableOptions:o})=>{const{widgetMenuSettings:s,setWidgetMenuSettings:a,setHasChanges:l,hasError:d,setHasError:p,planData:h}=(0,n.t0)(),x=()=>{const i=h?.plan?.features;return i?.[e.replace("-","_")]};return(0,c.jsxs)(ki.A,{as:"div",disableGutters:!0,sx:{p:"4px"},children:["sitemap"===e?(0,c.jsx)(Oi,{sitemap:i}):(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(Ii.A,{children:i.icon}),(0,c.jsxs)(g.A,{display:"flex",flexDirection:"row",alignItems:"center",children:[(0,c.jsx)(Z.A,{htmlFor:`ea11y-${e}-toggle`,children:(0,c.jsx)(Si.A,{primary:i.title})}),i?.pro&&!x()&&(0,c.jsx)(Fi,{source:"icon",childKey:e,childValue:i,enabled:x(),showIcon:!0})]})]}),(0,c.jsx)(Di.A,{sx:{top:"19px"},children:(0,c.jsx)(Fi,{source:"toggle",childKey:e,childValue:i,enabled:x(),children:(0,c.jsx)(Ni,{id:`ea11y-${e}-toggle`,checked:s[e]?.enabled||!1,onChange:()=>{return i=e,void a(e=>{const t={...e,[i]:{...e[i],enabled:!e[i]?.enabled}};return"sitemap"===i&&p({...d,sitemap:!e[i]?.enabled&&!(0,E.wv)(e[i]?.url)}),l(!0),window?.ea11yWidget?.toolsSettings&&window?.ea11yWidget?.widget&&(window.ea11yWidget.toolsSettings=t,window?.ea11yWidget?.widget.updateState()),e[i]&&r.K.sendEvent(r.m.toggleClicked,{state:e[i]?.enabled?"off":"on",type:i}),t});var i},disabled:!(!i?.pro||x())||!!s[e]?.enabled&&o})})})]},e)};2207==t.j&&(p=t(80984)),2207==t.j&&(b=t(92810));const Fi=({children:e,childKey:i,source:t,enabled:o,childValue:s,showIcon:n=!1})=>{const d=(0,c.jsxs)(si.A,{elevation:0,sx:{maxWidth:300},children:[(0,c.jsx)(_i.A,{title:(0,l.__)("Access more advanced features","pojo-accessibility")}),(0,c.jsx)(vi.A,{children:(0,c.jsx)(A.A,{variant:"body2",color:"text.secondary",children:(0,l.__)("Upgrade to get more customization and other pro features to boost your site.","pojo-accessibility")})}),(0,c.jsx)(fi.A,{children:(0,c.jsx)(m.A,{size:"medium",color:"promotion",variant:"contained",startIcon:(0,c.jsx)(p.A,{}),onClick:()=>{"screen-reader"===i&&"icon"===t&&((0,E.Fe)((0,k.b)(a.qQ.SCREEN_READER_ICON)),r.K.sendEvent(r.m.upgradeButtonClicked,{feature:"screen reader",component:"pro icon"})),"screen-reader"===i&&"toggle"===t&&((0,E.Fe)(a.qQ.SCREEN_READER_TOGGLE),r.K.sendEvent(r.m.upgradeButtonClicked,{feature:"screen reader",component:"toggle"})),ii.A_.REMOVE_BRANDING===i&&"icon"===t&&((0,E.Fe)((0,k.b)(a.qQ.ALLY_LABEL_ICON)),r.K.sendEvent(r.m.upgradeButtonClicked,{feature:"ally label",component:"pro icon"})),ii.A_.REMOVE_BRANDING===i&&"toggle"===t&&((0,E.Fe)((0,k.b)(a.qQ.ALLY_LABEL_TOGGLE)),r.K.sendEvent(r.m.upgradeButtonClicked,{feature:"ally label",component:"toggle"}))},children:(0,l.__)("Upgrade now","pojo-accessibility")})})]});return s?.pro||"pro"===s?(s?.pro||"pro"===s)&&o?e:(0,c.jsxs)(ni.A,{placement:"top",content:d,disableFocusListener:!0,PopperProps:{sx:{zIndex:9999999999}},onOpen:()=>{let e="",o="";"screen-reader"===i&&"toggle"===t?(e="screen reader",o="toggle"):"screen-reader"===i&&"icon"===t?(e="screen reader",o="icon"):ii.A_.REMOVE_BRANDING===i&&"icon"===t?(e="ally label",o="pro icon"):ii.A_.REMOVE_BRANDING===i&&"toggle"===t&&(e="ally label",o="toggle"),r.K.sendEvent(r.m.upgradeTooltipTriggered,{feature:e,component:o})},children:[n&&(0,c.jsx)(Zi,{color:"promotion",variant:"standard",icon:(0,c.jsx)(C.Qc,{}),size:"small"}),e]}):e},Zi=(0,s.I)(b.A)`
	margin-left: ${({theme:e})=>e.spacing(1)};
	height: 26px;
	width: 26px;

	.MuiChip-label {
		padding: 0;
	}
`,Wi=(0,s.I)(ai.A)`
	input {
		height: 56px !important;
	}
`,Ni=e=>(0,c.jsx)(Wi,{size:"medium",color:"info",...e});var Gi=t(54866);const Ki=({icon:e,size:i,radius:t,control:o,type:s})=>{const{iconDesign:a}=(0,n.t0)(),r=`lch(from ${a?.color||"#2563EB"} calc((50 - l) * 100) 0 0)`;let l=t||a?.cornerRadius?.radius+a?.cornerRadius?.unit,d=t||a?.cornerRadius?.radius-3+a?.cornerRadius?.unit,p=i,h=1,x=i,u=i;return"select"===o&&"text"===s&&(l=1,d=.5),"size"===o&&"text"===s&&(p=i>50?1.2*i:1*i),h=i>=64?2:i>=44&&i<64?1.5:1,64===i?(u=i-16,x=i-16,"text"===s&&(u=1.9*i-16,x=i-16)):44===i?(u=i-14,x=i-14,"text"===s&&(u=1.9*i-14,x=i-14)):(u=i-12,x=i-12,"text"===s&&(u=1.9*i-12,x=i-12)),(0,c.jsx)(Ui,{sx:{backgroundColor:a?.color,borderRadius:l,width:"text"===s?1.9*i:i,height:i},children:(0,c.jsxs)(qi,{sx:{border:h,borderColor:r,borderRadius:d,padding:i>=50?"12px":1,width:u,height:x},children:["custom"!==s&&(0,te.cloneElement)(e,{size:p/2.5}),"custom"===s&&(0,c.jsx)(Qi,{size:p/2.5})]})})},Ui=(0,s.I)(g.A)`
	display: flex;
	justify-content: center;
	align-items: center;
	padding: ${({theme:e})=>e.spacing(1)};
`,qi=(0,s.I)(g.A)`
	display: inline-flex;
	justify-content: center;
	align-items: center;
`,Qi=({size:e})=>{const{iconDesign:i}=(0,n.t0)(),t=`lch(from ${i?.color||"#fff"} calc((50 - l) * 100) 0 0)`;return(0,c.jsx)(Yi,{className:"custom-icon",style:{display:"flex",alignItems:"center",justifyContent:"center"},dangerouslySetInnerHTML:{__html:i?.custom?.svg},size:e,strokeColor:t})},Yi=(0,s.I)("div")`
	svg {
		font-size: ${({size:e})=>e}px;
		width: ${({size:e})=>e}px;
		fill: ${({strokeColor:e})=>e};

		path {
			fill: ${({strokeColor:e})=>e};
		}
	}
`,Xi=({iconType:e,option:i,clickHandler:t})=>{const{iconDesign:o}=(0,n.vS)(),[s,a]=(0,te.useState)(!1);(0,te.useEffect)(()=>{const t="custom"===i.value&&"select"===e;a(t)},[]);const r="select"===e?i.icon:ee(o.icon)?.icon,d="select"===e?44:i.fontSize,p="select"===e?100:null,h="select"===e?i.value:ee(o.icon)?.value;if("custom"===i.value&&!o.custom)return null;const x="select"===e?o.icon:o.size;return(0,c.jsxs)(Ji,{variant:"outlined",onClick:t(i.value),sx:{borderColor:x===i.value?"info.main":"divider",borderWidth:x===i.value?2:1},children:[s&&(0,c.jsx)(et,{label:(0,l.__)("Custom","pojo-accessibility"),size:"small"}),(0,c.jsx)(Ki,{icon:r,size:d,radius:p,color:o?.color,control:e,type:h}),(0,c.jsx)(Ie.A,{value:i.value,inputProps:{"aria-label":i.label},sx:{opacity:0,position:"absolute"}})]},i.value)},Ji=(0,s.I)(ke.A)`
	display: flex;
	flex-direction: column;
	flex-grow: 1;
	gap: 12px;
	align-items: center;
	justify-content: center;
	padding: 3px;
	min-width: 10px;
	width: 100px;
	min-height: 100px;
	box-shadow: ${({theme:e})=>e.shadows[0]};
	cursor: pointer;
	position: relative;

	:hover {
		box-shadow: 0 0 15px 0 rgb(37 99 235 / 0.15);
		border-color: ${({theme:e})=>e.palette.info.main};
	}
`,et=(0,s.I)(b.A)`
	position: absolute;
	top: 5px;
	left: 5px;
	font-size: 10px;
	height: 16px;
`;var it=t(57568),tt=t(3582);const ot=e=>{const[i,t]=(0,te.useState)(""),{accessibilityStatementData:o,setAccessibilityStatementData:s,showAccessibilityGeneratedInfotip:a}=(0,n.t0)(),d=(0,tt.useEntityRecords)("postType","page",{per_page:10}),p=(0,tt.useEntityRecords)("postType","page",{per_page:-1,search:i}),h=i.length>0&&!p?.hasResolved,x=(0,te.useMemo)(()=>i?.length>0&&p?.records?p?.records:d?.records||[],[i,d?.records,p?.records]),u=(0,te.useMemo)(()=>x?x.map(e=>({label:e.title.rendered,id:e.id,link:e.link,pageId:e.id})):[],[x]);return(0,c.jsx)(ni.A,{placement:"right-start",content:(0,c.jsx)(Ai,{}),disableHoverListener:!0,disableFocusListener:!0,PopperProps:{sx:{zIndex:99999999999}},open:a,children:(0,c.jsx)(it.A,{...e,options:u,renderInput:e=>(e=>(0,c.jsx)(st,{...e,placeholder:(0,l.__)("Search for a page","pojo-accessibility"),onChange:e=>{const i=e.target.value;t(i)},color:"info"}))(e),sx:{width:300},onChange:(e,i)=>{(e=>{s({...o,pageId:e?.id,link:e?.link,id:e?.id,label:e?.label}),r.K.sendEvent(r.m.statementPageSelected,{page:e?.link})})(i)},getOptionLabel:e=>e.label||"",loading:h,loadingText:(0,l.__)("Searching…","pojo-accessibility"),noOptionsText:h?(0,l.__)("Searching…","pojo-accessibility"):(0,l.__)("No pages found","pojo-accessibility"),clearOnBlur:!1,value:o})})},st=(0,s.I)(he.A)`
	& .MuiAutocomplete-input,
	& .MuiAutocomplete-input:focus,
	& .MuiAutocomplete-input:focus-visible {
		border: none;
		outline: none;
		box-shadow: none;
	}
`;if(9884==t.j)var nt=t(58116);if(9884==t.j)var at=t(84918);const rt={visits:{title:(0,l.__)("Widget loads","pojo-accessibility"),infotipDescription:(0,l.__)("This is how many times your widget loads for each unique IP/device in a 24-hour span across your sites this month. Upgrade if you’re nearing your limit to keep all features available.","pojo-accessibility")},scanner:{title:(0,l.__)("Scanned URLs","pojo-accessibility"),infotipDescription:(0,l.__)("This shows how many URLs you’ve scanned for accessibility in total. If you’re reaching your limit, consider upgrading to keep scanning new pages.","pojo-accessibility")},ai:{title:(0,l.__)("AI credits used","pojo-accessibility"),infotipDescription:(0,l.__)("This is how many AI credits you’ve used to resolve issues or to generate content. Upgrade if you’re nearing your plan limit to keep full functionality.","pojo-accessibility"),lockedDescription:(0,l.__)("Upgrade your plan to unlock AI credits for generating alt‑text, one‑click accessibility fixes, and more.","pojo-accessibility")}},lt=9884==t.j?({type:e,quotaData:i})=>{const t=0===i?.allowed?0:Math.round(i?.used/i?.allowed*100),o=0===i?.allowed;return(0,c.jsxs)(ct,{children:[(0,c.jsxs)(g.A,{display:"flex",justifyContent:"space-between",children:[(0,c.jsxs)(A.A,{variant:"body2",color:o?"text.disabled":"text.secondary",display:"flex",alignItems:"center",gap:.5,sx:{fontSize:"12px"},children:[rt[e]?.title,(0,c.jsx)(ni.A,{placement:"bottom",PopperProps:{sx:{width:"210px"},disablePortal:!0},content:(0,c.jsx)(A.A,{variant:"body2",color:"text.secondary",fontSize:"0.75rem",padding:2,children:o?rt[e]?.lockedDescription:rt[e]?.infotipDescription}),children:o?(0,c.jsx)(nt.A,{sx:{fontSize:"medium",color:"text.primary",opacity:.5}}):(0,c.jsx)(oi.A,{sx:{fontSize:"medium"}})})]}),(0,c.jsx)(g.A,{display:"flex",flexDirection:"row",gap:.5,alignItems:"center",children:(0,c.jsx)(A.A,{variant:"body2",color:o?"text.disabled":"text.primary",sx:{fontSize:"12px"},children:o?"0/0":`${(0,E.g6)(i?.used)} / ${(0,E.g6)(i?.allowed)}`})})]}),(0,c.jsx)(at.A,{sx:{"& .MuiLinearProgress-bar":{animation:"none",backgroundColor:o&&"text.disabled"},animation:"none"},value:t,variant:"buffer",valueBuffer:100,color:t<80?"primary":t>=80&&t<95?"warning":"error"})]})}:null,ct=(0,s.I)(g.A)`
	display: inline-flex;
	flex-direction: column;
	gap: ${({theme:e})=>e.spacing(1)};
	width: 100%;
	margin-bottom: ${({theme:e})=>e.spacing(1)};
`;if(9884==t.j)var dt=t(94382);if(9884==t.j)var pt=t(42645);const ht=9884==t.j?()=>{const{planData:e,openSidebar:i}=(0,n.t0)();if(!e?.scannedPages||!e?.aiCredits)return null;const{scannedPages:t,aiCredits:o}=e,s=Math.round(t.used/t.allowed*100),a=Math.round(o.used/o.allowed*100),r=s>=80&&s<100||a>=80&&a<100;return s>=100||a>=100?(0,c.jsx)(_.A,{title:(0,l.__)("You’re reached your plan’s limit","pojo-accessibility"),children:(0,c.jsx)(dt.A,{sx:{color:"error.dark"},fontSize:"16px"})}):r?(0,c.jsx)(_.A,{title:(0,l.__)("You’re nearing your plan’s limit","pojo-accessibility"),children:(0,c.jsx)(pt.A,{sx:{color:"warning.light",justifySelf:i?"auto":"right",alignSelf:i?"auto":"start",fontSize:i?"inherit":"16px",position:i?"relative":"absolute",top:"0",right:i?"auto":"10px"}})}):null}:null;if(9884==t.j)var xt=t(13581);if(9884==t.j)var ut=t(72608);var gt=t(31370);const mt=9884==t.j?({keyName:e,item:i})=>{const{openSidebar:t,selectedMenu:o,setSelectedMenu:s,planData:a}=(0,n.t0)(),{isElementorOne:l}=(0,D.F)(),[d,p]=(0,te.useState)({widget:!0}),[h,x]=(0,te.useState)({popupPositionAbove:0,popupPositionBefore:0}),u=(0,te.useRef)(null),g=e,m=a?.plan?.features?Object.keys(a.plan.features).filter(e=>Boolean(a.plan.features[e])&&"false"!==a.plan.features[e]):null,b=(e,i,t)=>{s({parent:i,child:t||null}),window.location.hash=t||i,r.K.sendEvent(r.m.menuButtonClicked,{buttonName:t||i})},j=e=>!l&&m&&e.proIcon&&!m.includes(e.proIcon),y=()=>{if(u.current&&!t&&i?.children){const e=u.current.getBoundingClientRect(),i="rtl"===document.dir;x({popupPositionAbove:e.top,popupPositionBefore:i?window.innerWidth-e.left+2:e.right+2})}},f=!t,v=!!i?.children,A=d[g];return(0,c.jsxs)(_t,{ref:u,hasChildItems:v,isSidebarCollapsed:f,disableGutters:!0,disablePadding:!0,dense:!0,onMouseEnter:y,onFocus:y,children:[(0,c.jsxs)(bt,{onClick:()=>{return v?(i.name,e=g,p(i=>({...i,[e]:!i[e]})),void r.K.sendEvent(r.m.menuButtonClicked,{buttonName:e})):b(i.name,g);var e},selected:g===o?.parent&&(!o?.child||f),children:[(0,c.jsx)(_.A,{title:i?.name,placement:"right",disableHoverListener:t||v,children:(0,c.jsx)(jt,{"aria-hidden":!f,isSidebarCollapsed:f,children:i.icon})}),!f&&(0,c.jsxs)(c.Fragment,{children:[(0,c.jsx)(yt,{primary:i.name}),!j(i)&&i?.infotip,j(i)&&(0,c.jsx)(vt,{color:"promotion",variant:"standard",icon:(0,c.jsx)(gt.A,{size:"tiny"}),size:"tiny"}),v&&(0,c.jsx)(ut.A,{in:!A,children:(0,c.jsx)(xt.A,{})})]})]}),v&&(0,c.jsxs)(kt,{isSidebarCollapsed:f,isItemExpanded:A,popupPosition:h,children:[f&&(0,c.jsx)(At,{primary:i.name}),(0,c.jsx)(O.A,{disablePadding:!0,dense:!0,sx:{pt:1},children:Object.entries(i?.children).map(([e,i])=>(0,c.jsx)(ft,{dense:!0,children:(0,c.jsxs)(Ct,{isSidebarCollapsed:f,selected:e===o?.child,onClick:()=>b(i.name,g,e),children:[(0,c.jsx)(wt,{primary:i?.name,primaryTypographyProps:{color:"text.secondary"}}),!j(i)&&i?.infotip,j(i)&&(0,c.jsx)(vt,{color:"promotion",variant:"standard",icon:(0,c.jsx)(gt.A,{size:"tiny"}),size:"tiny"})]})},e))})]})]},i?.key)}:null,bt=(0,s.I)(o.A)`
	justify-content: center;
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	padding: ${({theme:e})=>e.spacing(.5,1.5)};
`,jt=(0,s.I)(Ii.A,{shouldForwardProp:e=>"isSidebarCollapsed"!==e})`
	/* For smoother sidebar */
	padding: ${({isSidebarCollapsed:e,theme:i})=>e?i.spacing(.5):"auto"};
	margin-inline-end: ${({isSidebarCollapsed:e,theme:i})=>e?"0 !important":i.spacing(1)};
`,yt=(0,s.I)(Si.A)`
	text-align: start;
	white-space: nowrap;
`,ft=(0,s.I)(ki.A)`
	padding: 0;
`,vt=(0,s.I)(b.A)`
	height: 26px;
	width: 26px;

	.MuiChip-label {
		padding: 0;
	}
`,_t=(0,s.I)(ki.A,{shouldForwardProp:e=>"hasChildItems"!==e&&"isSidebarCollapsed"!==e})`
	position: relative;
	flex-direction: column;
	align-items: stretch;

	${({hasChildItems:e,isSidebarCollapsed:i})=>e&&i&&"\n\t\t&:hover > div:last-child,\n\t\t&:focus-within > div:last-child {\n\t\t\tdisplay: block;\n\t\t}\n\t"}
`,At=(0,s.I)(Si.A)`
	color: ${({theme:e})=>e.palette.text.secondary};
	padding: ${({theme:e})=>e.spacing(1,2)};
	margin: 0;
	text-align: start;
`,Ct=(0,s.I)(o.A,{shouldForwardProp:e=>"isSidebarCollapsed"!==e})`
	color: ${({isSidebarCollapsed:e,theme:i})=>e?i.palette.text.primary:i.palette.text.secondary};
	padding: ${({theme:e})=>e.spacing(.5)};
	padding-inline: ${({isSidebarCollapsed:e,theme:i})=>e?i.spacing(2):i.spacing(5,1.5)};
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
`,wt=(0,s.I)(Si.A)`
	text-align: start;
	white-space: nowrap;
`,kt=(0,s.I)("div",{shouldForwardProp:e=>"isSidebarCollapsed"!==e&&"isItemExpanded"!==e&&"popupPosition"!==e})`
	${({isSidebarCollapsed:e,isItemExpanded:i,popupPosition:t,theme:o})=>e?`\n\t\t\t\tdisplay: none;\n\t\t\t\tposition: fixed;\n\t\t\t\tinset-block-start: ${t?.popupPositionAbove||0}px;\n\t\t\t\tinset-inline-start: ${t?.popupPositionBefore||0}px;\n\t\t\t\tmin-width: 200px;\n\t\t\t\tpadding: ${o.spacing(1)};\n\t\t\t\tbackground-color: ${o.palette.background.paper};\n\t\t\t\tbox-shadow: ${o.shadows[8]};\n\t\t\t\tborder-radius: ${o.shape.borderRadius}px;\n\t\t\t`:i?"display: block;":"display: none;"}
`;var It=t(76814),Dt=t(75324),St=t(59598);const Et=9884==t.j?()=>{const{planData:e}=(0,n.t0)(),i=(0,te.useRef)(null),[t,o]=(0,te.useState)(!1),[s,d]=(0,te.useState)(!1),{isConnected:h}=(0,D.F)(),{redirectToConnect:x}=(0,St.A)();(0,te.useEffect)(()=>{new Date(e?.plan?.next_cycle_date)<new Date&&d(!0)},[e?.plan?.next_cycle_date]);const u="Free"===e?.plan?.name,j=()=>o(!t),y=(0,xe.A)({variant:"popover",popupId:"popupMenuCollapsedSidebar"}),f=()=>(0,c.jsxs)(g.A,{display:"flex",alignItems:"center",gap:1,whiteSpace:"nowrap",...(0,ue.cO)(y),children:[(0,c.jsx)(A.A,{variant:"body2",as:"div",children:(0,l.__)("Current plan","pojo-accessibility")}),s?(0,c.jsx)(b.A,{variant:"filled",color:"error",label:(0,l.__)("Expired","pojo-accessibility"),size:"tiny"}):(0,c.jsx)(b.A,{variant:"filled",color:h?"default":"error",label:h?e?.plan?.name:(0,l.__)("Not connected","pojo-accessibility"),size:"tiny"}),(0,c.jsx)(ht,{})]});return(0,c.jsx)(Mt,{children:(0,c.jsxs)(Pt,{ref:i,children:[(0,c.jsxs)(si.A,{elevation:0,sx:{overflow:"visible"},children:[(0,c.jsx)(zt,{onClick:j,children:(0,c.jsx)(Rt,{title:(0,c.jsx)(f,{}),action:(0,c.jsx)(ut.A,{in:!t,children:(0,c.jsx)(xt.A,{})}),disableActionOffset:!0})}),(0,c.jsx)(M,{...(0,ue.NA)(y),closeAction:y.close,showUpgradeButton:"false",anchorOrigin:{vertical:"top",horizontal:"center"},transformOrigin:{vertical:"bottom",horizontal:"center"},open:t,onClose:j,anchorEl:i.current})]}),(0,c.jsx)(si.A,{elevation:0,children:(0,c.jsx)($t,{children:h||s?(0,c.jsx)(m.A,{variant:"outlined",startIcon:u||s?(0,c.jsx)(p.A,{}):null,size:"small",fullWidth:!0,color:u||s?"promotion":"secondary",onClick:()=>{r.K.sendEvent(r.m.upgradeButtonClicked,{feature:"add visits",component:"quota counter"}),(0,E.Fe)((0,k.b)(a.qQ.ADD_VISITS))},children:u||s?(0,l.__)("Upgrade plan","pojo-accessibility"):(0,l.__)("View more plans","pojo-accessibility")}):(0,c.jsx)(m.A,{variant:"outlined",size:"small",fullWidth:!0,color:"promotion",onClick:x,children:(0,l.__)("Connect to start","pojo-accessibility")})})})]})})}:null,Mt=(0,s.I)(g.A)`
	display: flex;
	flex-direction: row;
	align-items: start;
	justify-content: center;
	max-width: 224px;
	margin: ${({theme:e})=>e.spacing(1)};
	padding: 0;

	border-radius: ${({theme:e})=>e.shape.borderRadius}px;

	&:hover,
	&:focus-within {
		background-color: ${({theme:e})=>e.palette.action.hover};
	}
`,Pt=(0,s.I)(Dt.A)`
	display: flex;
	flex-direction: column;
	gap: ${({theme:e})=>e.spacing(1)};

	border: none;
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;
	padding: ${({theme:e})=>e.spacing(1.5)};
	width: 100%;
	background-color: transparent;

	& .MuiPaper-root {
		background-color: transparent;
	}
`,$t=(0,s.I)(vi.A)`
	padding: 0;
	background-color: transparent;

	:last-child {
		padding-bottom: 0;
	}
`,Rt=(0,s.I)(_i.A)`
	padding: ${({theme:e})=>e.spacing(.5)} 0;
	background-color: transparent;
`,zt=(0,s.I)(It.A)`
	background-color: transparent;
	border-radius: ${({theme:e})=>e.shape.borderRadius}px;

	&:hover {
		background-color: transparent;
	}
	.MuiCardActionArea-focusHighlight {
		background-color: transparent;
	}
`},62883(e,i,t){t.d(i,{b:()=>o});const o=(e,i=24)=>null==e?"":e.length<=i?e:e.slice(0,i-3)+"..."},74565(e,i,t){t.d(i,{Fe:()=>a,GZ:()=>s,g6:()=>l,ln:()=>n,n4:()=>r,wv:()=>o});const o=e=>/^(https?):\/\/([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(:\d{1,5})?(\/.*)?$/i.test(e),s=e=>/^#[A-Za-z0-9_.-]+$/.test(e),n=(e,i=10)=>{const t=[];for(let o=0;o<e.length;o+=i)t.push(e.slice(o,o+i));return t},a=(e,i="_blank")=>{window.open(e,i)},r=(e,i)=>i&&e?parseFloat((i/e*100).toFixed(1)):0,l=e=>{if(e>=1e6){const i=e/1e6;return i%1==0?`${i}M`:`${i.toFixed(1)}M`}if(e>=1e3){const i=e/1e3;return i%1==0?`${i}K`:`${i.toFixed(1)}K`}return e}},96989(e,i,t){t.d(i,{A:()=>r});var o=t(50602),s=t(95231),n=t(10790);const a=(0,s.I)(o.A)`
	&.MuiButton-colorInfo:focus,
	&.MuiButton-colorInfo:focus-visible {
		box-shadow: 0 0 0 3px #5e9ed6;
	}
`,r=({...e})=>"info"===e.color?(0,n.jsx)(a,{...e}):(0,n.jsx)(o.A,{...e})},99418(e,i,t){t.r(i),t.d(i,{default:()=>S});var o=t(78048),s=t(95231),n=t(46097),a=t(97579),r=t(40730),l=t(99028),c=t(49775),d=t(98053),p=t(75260),h=t(85848),x=t(9626),u=t(70276),g=t(27723),m=t(74565),b=t(10790);const j=(0,s.I)(r.A)`
	padding: ${({theme:e})=>e.spacing(2)};
	margin-block: ${({theme:e})=>e.spacing(4)};
	max-width: 1200px;
	margin-left: auto;
	margin-right: auto;
`,y=(0,s.I)(o.A)`
	display: flex;
	align-items: center;
	justify-content: space-between;
`,f=(0,s.I)(d.A)`
	input {
		height: 56px !important;
	}
`,v=(0,s.I)(o.A)`
	display: flex;
	flex-direction: column;
	gap: 16px;
	align-items: flex-start;
	justify-content: start;
	padding-top: 16px;
`,_=(0,s.I)(l.A)`
	display: flex;
	align-items: center;
	gap: 8px;
	white-space: nowrap;
	padding-top: 7px;
`,A=(0,s.I)(p.A)`
	width: 200px;
	input {
		height: 36px;
	}
`,C=(0,s.I)(h.A)`
	display: flex;
	gap: 8px;
	align-items: center;
`,w=()=>{const{skipToContentSettings:e,setSkipToContentSettings:i,skipToContentHasChanges:t,setSkipToContentHasChanges:s,hasError:n,setHasError:r,setHasChanges:l}=(0,x.t0)(),d=(0,g.__)("This feature allows visitors with visual assistive tools to skip to the main content of each page they’re viewing.","pojo-accessibility"),p=(0,g.__)("This is the HTML ID of the main content area on your pages. Changing this is only needed if your theme uses a custom ID instead of the default #main-content.","pojo-accessibility");return(0,b.jsxs)(j,{variant:"outlined",children:[(0,b.jsxs)(y,{children:[(0,b.jsxs)(C,{variant:"subtitle1",id:"ea11y-skip-to-content-toggle","aria-description":d,children:[(0,g.__)("Skip to main content","pojo-accessibility"),(0,b.jsx)(c.A,{tabIndex:"0",content:(0,b.jsxs)(o.A,{sx:{p:2,maxWidth:"250px"},children:[(0,b.jsx)(h.A,{variant:"subtitle2",sx:{mb:1},children:(0,g.__)("Skip to main content","pojo-accessibility")}),(0,b.jsx)(h.A,{variant:"body2",children:d})]}),placement:"right",arrow:!0,children:(0,b.jsx)(a.A,{fontSize:"small"})})]}),(0,b.jsx)(f,{size:"medium",color:"info",checked:e?.enabled||!1,onChange:()=>{i({...e,enabled:!e.enabled}),r({...n,sitemap:!e?.enabled&&!(0,m.GZ)(e?.anchor)}),s(!0),l(!0)},inputProps:{"area-labelledby":"ea11y-skip-to-content-toggle"}})]}),(0,b.jsx)(h.A,{variant:"body1",children:(0,g.__)("Add skip to content link when using keyboard","pojo-accessibility")}),(0,b.jsxs)(v,{children:[(0,b.jsxs)(_,{htmlFor:"skip-to-content-anchor","aria-description":p,children:[(0,g.__)("Main content ID","pojo-accessibility"),(0,b.jsx)(c.A,{tabIndex:"0",content:(0,b.jsxs)(o.A,{sx:{p:2,maxWidth:"250px"},children:[(0,b.jsx)(h.A,{variant:"subtitle2",sx:{mb:1},children:(0,g.__)("Skip to main content","pojo-accessibility")}),(0,b.jsx)(h.A,{variant:"body2",children:p})]}),placement:"right",arrow:!0,children:(0,b.jsx)(a.A,{fontSize:"small"})})]}),(0,b.jsxs)(o.A,{children:[(0,b.jsx)(A,{id:"skip-to-content-anchor",type:"url",onChange:t=>{i({...e,anchor:t.target.value});const o=(0,m.GZ)(t.target.value);r({...n,skipToContent:!o}),s(o),l(o)},onBlur:()=>{t&&u.K.sendEvent(u.m.fieldContentUpdated,{fieldName:"skip-to-content-anchor",value:e.anchor})},value:e.anchor,error:n.skipToContent,size:"small",variant:"outlined"}),n.skipToContent&&(0,b.jsx)(h.A,{as:"p",variant:"caption",color:"error",sx:{marginTop:"4px"},children:(0,g.__)("Please enter valid ID!","pojo-accessibility")})]})]})]})};var k=t(24200),I=t(41094),D=t(86087);const S=()=>((0,D.useEffect)(()=>{u.K.sendEvent(u.m.pageView,{page:"Capabilities"})},[]),(0,b.jsxs)(I.fM,{children:[(0,b.jsxs)(I.ax,{children:[(0,b.jsx)(M,{variant:"h5",children:(0,g.__)("Capabilities","pojo-accessibility")}),(0,b.jsxs)(E,{children:[(0,b.jsx)(k.DG,{}),(0,b.jsx)(k.P6,{})]}),(0,b.jsx)(w,{}),(0,b.jsx)(n.gp,{})]}),(0,b.jsx)(n.dT,{})]})),E=(0,s.I)(o.A)`
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: ${({theme:e})=>e.spacing(4)};
	max-width: 1200px;
	margin-left: auto;
	margin-right: auto;
`,M=(0,s.I)(I.QP)`
	max-width: 1200px;
	margin: 0 auto 24px;
`}}]);