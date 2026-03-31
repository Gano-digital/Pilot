"use strict";(globalThis.webpackChunkpojo_accessibility=globalThis.webpackChunkpojo_accessibility||[]).push([[7963],{19621(e,t,i){i.r(t),i.d(t,{default:()=>j});var o=i(78048),n=i(50602),a=i(5322),c=i(85848),r=i(95231),s=i(9626),l=i(54866),d=i(59921),h=i(208),p=i(86087),m=i(27723),y=i(93832),g=i(47064),x=i(42679),u=i(10790);const j=()=>{const{open:e,close:t}=(0,s.hS)(!0),{error:i}=(0,h.m)(),[o,a]=(0,p.useState)(!1),{isElementorOne:r}=(0,x.F)(),j=()=>a(!1);return(0,u.jsxs)(u.Fragment,{children:[(0,u.jsx)($,{open:e,onClose:t,logo:!1,title:(0,m.__)("Fix mismatched URL","pojo-accessibility"),showCancelButton:!1,showApproveButton:!1,maxWidth:"lg",fullWidth:!0,dividers:!0,children:(0,u.jsxs)(f,{children:[(0,u.jsx)(v,{variant:"h4",children:(0,m.__)("Choose how to reconnect Ally to your site","pojo-accessibility")}),(0,u.jsx)(C,{variant:"body1",children:(0,m.__)("Your license key does not match your current domain, causing a mismatch. This is most likely due to a change in the domain URL of your site.","pojo-accessibility")}),(0,u.jsxs)(A,{children:[(0,u.jsxs)(b,{children:[(0,u.jsx)(w,{variant:"h6",marginBottom:3,children:(0,m.__)("Update the connected URL","pojo-accessibility")}),(0,u.jsx)(_,{variant:"body1",marginBottom:3,children:(0,m.__)("For cases where you’re moving the same site from staging to production or changing from HTTP to HTTPs.","pojo-accessibility")}),(0,u.jsx)(n.A,{variant:"contained",onClick:async()=>{if(r)window.location.href=d.iz;else{try{await g.A.initConnect("update"),window.location.reload()}catch(e){i((0,m.__)("An error occurred.","pojo-accessibility"))}t()}},color:"primary",children:(0,m.__)("Update URL","pojo-accessibility")})]}),(0,u.jsxs)(b,{children:[(0,u.jsx)(w,{variant:"h6",marginBottom:3,children:(0,m.__)("Connect the URL as a new site","pojo-accessibility")}),(0,u.jsx)(_,{variant:"body1",marginBottom:3,children:(0,m.__)("For when you want to connect the plugin to a new site entirely—deleting the previous history.","pojo-accessibility")}),(0,u.jsx)(n.A,{variant:"contained",onClick:()=>a(!0),color:"primary",children:(0,m.__)("Connect new site","pojo-accessibility")})]})]})]})}),o&&(0,u.jsx)(l.A,{onClose:j,onCancel:j,title:(0,m.__)("Are you sure you want to connect as a new site?","pojo-accessibility"),approveText:(0,m.__)("Connect","pojo-accessibility"),onApprove:async()=>{if(r)window.location.href=d.iz;else{try{a(!1),await g.A.clearSession(),window.location.href=(0,y.addQueryArgs)(window.location.href,{action:"connect"})}catch(e){i((0,m.__)("An error occurred.","pojo-accessibility"))}t()}},children:(0,u.jsx)(c.A,{variant:"body1",children:(0,m.__)("Connecting as a new site will delete data related to the current site.","pojo-accessibility")})})]})},b=(0,r.I)(o.A)`
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	width: 38%;
	padding: 40px;

	border: 1px solid rgb(0 0 0 / 0.12);
	border-radius: 4px;

	text-align: center;

	@media screen and (max-width: 768px) {
		width: 100%;
	}

	& span {
		margin-right: 24px;
	}
`,w=(0,r.I)(c.A)`
	font-size: 16px;
	color: ${({theme:e})=>e.palette.text.primary};
`,_=(0,r.I)(c.A)`
	font-size: 14px;
	color: ${({theme:e})=>e.palette.text.secondary};
`,f=(0,r.I)(a.A)`
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	padding-left: ${({theme:e})=>e.spacing(8)};
	padding-right: ${({theme:e})=>e.spacing(8)};
`,A=(0,r.I)(a.A)`
	justify-content: space-between;
	display: flex;
	margin-top: 80px;
	margin-bottom: 80px;
`,v=(0,r.I)(c.A)`
	color: ${({theme:e})=>e.palette.text.primary};
	margin-top: ${({theme:e})=>e.spacing(5)};
	margin-bottom: ${({theme:e})=>e.spacing(1)};
`,C=(0,r.I)(c.A)`
	color: ${({theme:e})=>e.palette.text.secondary};
	margin-bottom: ${({theme:e})=>e.spacing(1)};
	text-align: center;
	width: 70%;
`,$=(0,r.I)(l.A)`
	margin-left: 10%;
	& .MuiDialogContent-dividers {
		border-bottom: none;
	}
`}}]);