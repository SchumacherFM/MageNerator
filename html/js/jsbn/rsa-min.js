// https://github.com/jasondavies/jsbn
//
// Copyright (c) 2005  Tom Wu
// All Rights Reserved.
// See "LICENSE" for details.
var dbits,canary=0xdeadbeefcafe,j_lm=15715070==(canary&16777215);function BigInteger(a,b,c){null!=a&&("number"==typeof a?this.fromNumber(a,b,c):null==b&&"string"!=typeof a?this.fromString(a,256):this.fromString(a,b))}function nbi(){return new BigInteger(null)}function am1(a,b,c,d,e,f){for(;0<=--f;){var g=b*this[a++]+c[d]+e,e=Math.floor(g/67108864);c[d++]=g&67108863}return e}
function am2(a,b,c,d,e,f){for(var g=b&32767,b=b>>15;0<=--f;){var h=this[a]&32767,i=this[a++]>>15,m=b*h+i*g,h=g*h+((m&32767)<<15)+c[d]+(e&1073741823),e=(h>>>30)+(m>>>15)+b*i+(e>>>30);c[d++]=h&1073741823}return e}function am3(a,b,c,d,e,f){for(var g=b&16383,b=b>>14;0<=--f;){var h=this[a]&16383,i=this[a++]>>14,m=b*h+i*g,h=g*h+((m&16383)<<14)+c[d]+e,e=(h>>28)+(m>>14)+b*i;c[d++]=h&268435455}return e}
j_lm&&"Microsoft Internet Explorer"==navigator.appName?(BigInteger.prototype.am=am2,dbits=30):j_lm&&"Netscape"!=navigator.appName?(BigInteger.prototype.am=am1,dbits=26):(BigInteger.prototype.am=am3,dbits=28);BigInteger.prototype.DB=dbits;BigInteger.prototype.DM=(1<<dbits)-1;BigInteger.prototype.DV=1<<dbits;var BI_FP=52;BigInteger.prototype.FV=Math.pow(2,BI_FP);BigInteger.prototype.F1=BI_FP-dbits;BigInteger.prototype.F2=2*dbits-BI_FP;var BI_RM="0123456789abcdefghijklmnopqrstuvwxyz",BI_RC=[],rr,vv;
rr=48;for(vv=0;9>=vv;++vv)BI_RC[rr++]=vv;rr=97;for(vv=10;36>vv;++vv)BI_RC[rr++]=vv;rr=65;for(vv=10;36>vv;++vv)BI_RC[rr++]=vv;function int2char(a){return BI_RM.charAt(a)}function intAt(a,b){var c=BI_RC[a.charCodeAt(b)];return c==null?-1:c}function bnpCopyTo(a){for(var b=this.t-1;b>=0;--b)a[b]=this[b];a.t=this.t;a.s=this.s}function bnpFromInt(a){this.t=1;this.s=a<0?-1:0;a>0?this[0]=a:a<-1?this[0]=a+DV:this.t=0}function nbv(a){var b=nbi();b.fromInt(a);return b}
function bnpFromString(a,b){var c;if(b==16)c=4;else if(b==8)c=3;else if(b==256)c=8;else if(b==2)c=1;else if(b==32)c=5;else if(b==4)c=2;else{this.fromRadix(a,b);return}this.s=this.t=0;for(var d=a.length,e=false,f=0;--d>=0;){var g=c==8?a[d]&255:intAt(a,d);if(g<0)a.charAt(d)=="-"&&(e=true);else{e=false;if(f==0)this[this.t++]=g;else if(f+c>this.DB){this[this.t-1]=this[this.t-1]|(g&(1<<this.DB-f)-1)<<f;this[this.t++]=g>>this.DB-f}else this[this.t-1]=this[this.t-1]|g<<f;f=f+c;f>=this.DB&&(f=f-this.DB)}}if(c==
8&&(a[0]&128)!=0){this.s=-1;f>0&&(this[this.t-1]=this[this.t-1]|(1<<this.DB-f)-1<<f)}this.clamp();e&&BigInteger.ZERO.subTo(this,this)}function bnpClamp(){for(var a=this.s&this.DM;this.t>0&&this[this.t-1]==a;)--this.t}
function bnToString(a){if(this.s<0)return"-"+this.negate().toString(a);if(a==16)a=4;else if(a==8)a=3;else if(a==2)a=1;else if(a==32)a=5;else if(a==4)a=2;else return this.toRadix(a);var b=(1<<a)-1,c,d=false,e="",f=this.t,g=this.DB-f*this.DB%a;if(f-- >0){if(g<this.DB&&(c=this[f]>>g)>0){d=true;e=int2char(c)}for(;f>=0;){if(g<a){c=(this[f]&(1<<g)-1)<<a-g;c=c|this[--f]>>(g=g+(this.DB-a))}else{c=this[f]>>(g=g-a)&b;if(g<=0){g=g+this.DB;--f}}c>0&&(d=true);d&&(e=e+int2char(c))}}return d?e:"0"}
function bnNegate(){var a=nbi();BigInteger.ZERO.subTo(this,a);return a}function bnAbs(){return this.s<0?this.negate():this}function bnCompareTo(a){var b=this.s-a.s;if(b!=0)return b;var c=this.t,b=c-a.t;if(b!=0)return this.s<0?-b:b;for(;--c>=0;)if((b=this[c]-a[c])!=0)return b;return 0}function nbits(a){var b=1,c;if((c=a>>>16)!=0){a=c;b=b+16}if((c=a>>8)!=0){a=c;b=b+8}if((c=a>>4)!=0){a=c;b=b+4}if((c=a>>2)!=0){a=c;b=b+2}a>>1!=0&&(b=b+1);return b}
function bnBitLength(){return this.t<=0?0:this.DB*(this.t-1)+nbits(this[this.t-1]^this.s&this.DM)}function bnpDLShiftTo(a,b){var c;for(c=this.t-1;c>=0;--c)b[c+a]=this[c];for(c=a-1;c>=0;--c)b[c]=0;b.t=this.t+a;b.s=this.s}function bnpDRShiftTo(a,b){for(var c=a;c<this.t;++c)b[c-a]=this[c];b.t=Math.max(this.t-a,0);b.s=this.s}
function bnpLShiftTo(a,b){var c=a%this.DB,d=this.DB-c,e=(1<<d)-1,f=Math.floor(a/this.DB),g=this.s<<c&this.DM,h;for(h=this.t-1;h>=0;--h){b[h+f+1]=this[h]>>d|g;g=(this[h]&e)<<c}for(h=f-1;h>=0;--h)b[h]=0;b[f]=g;b.t=this.t+f+1;b.s=this.s;b.clamp()}
function bnpRShiftTo(a,b){b.s=this.s;var c=Math.floor(a/this.DB);if(c>=this.t)b.t=0;else{var d=a%this.DB,e=this.DB-d,f=(1<<d)-1;b[0]=this[c]>>d;for(var g=c+1;g<this.t;++g){b[g-c-1]=b[g-c-1]|(this[g]&f)<<e;b[g-c]=this[g]>>d}d>0&&(b[this.t-c-1]=b[this.t-c-1]|(this.s&f)<<e);b.t=this.t-c;b.clamp()}}
function bnpSubTo(a,b){for(var c=0,d=0,e=Math.min(a.t,this.t);c<e;){d=d+(this[c]-a[c]);b[c++]=d&this.DM;d=d>>this.DB}if(a.t<this.t){for(d=d-a.s;c<this.t;){d=d+this[c];b[c++]=d&this.DM;d=d>>this.DB}d=d+this.s}else{for(d=d+this.s;c<a.t;){d=d-a[c];b[c++]=d&this.DM;d=d>>this.DB}d=d-a.s}b.s=d<0?-1:0;d<-1?b[c++]=this.DV+d:d>0&&(b[c++]=d);b.t=c;b.clamp()}
function bnpMultiplyTo(a,b){var c=this.abs(),d=a.abs(),e=c.t;for(b.t=e+d.t;--e>=0;)b[e]=0;for(e=0;e<d.t;++e)b[e+c.t]=c.am(0,d[e],b,e,0,c.t);b.s=0;b.clamp();this.s!=a.s&&BigInteger.ZERO.subTo(b,b)}function bnpSquareTo(a){for(var b=this.abs(),c=a.t=2*b.t;--c>=0;)a[c]=0;for(c=0;c<b.t-1;++c){var d=b.am(c,b[c],a,2*c,0,1);if((a[c+b.t]=a[c+b.t]+b.am(c+1,2*b[c],a,2*c+1,d,b.t-c-1))>=b.DV){a[c+b.t]=a[c+b.t]-b.DV;a[c+b.t+1]=1}}a.t>0&&(a[a.t-1]=a[a.t-1]+b.am(c,b[c],a,2*c,0,1));a.s=0;a.clamp()}
function bnpDivRemTo(a,b,c){var d=a.abs();if(!(d.t<=0)){var e=this.abs();if(e.t<d.t){b!=null&&b.fromInt(0);c!=null&&this.copyTo(c)}else{c==null&&(c=nbi());var f=nbi(),g=this.s,a=a.s,h=this.DB-nbits(d[d.t-1]);if(h>0){d.lShiftTo(h,f);e.lShiftTo(h,c)}else{d.copyTo(f);e.copyTo(c)}d=f.t;e=f[d-1];if(e!=0){var i=e*(1<<this.F1)+(d>1?f[d-2]>>this.F2:0),m=this.FV/i,i=(1<<this.F1)/i,l=1<<this.F2,k=c.t,n=k-d,j=b==null?nbi():b;f.dlShiftTo(n,j);if(c.compareTo(j)>=0){c[c.t++]=1;c.subTo(j,c)}BigInteger.ONE.dlShiftTo(d,
j);for(j.subTo(f,f);f.t<d;)f[f.t++]=0;for(;--n>=0;){var o=c[--k]==e?this.DM:Math.floor(c[k]*m+(c[k-1]+l)*i);if((c[k]=c[k]+f.am(0,o,c,n,0,d))<o){f.dlShiftTo(n,j);for(c.subTo(j,c);c[k]<--o;)c.subTo(j,c)}}if(b!=null){c.drShiftTo(d,b);g!=a&&BigInteger.ZERO.subTo(b,b)}c.t=d;c.clamp();h>0&&c.rShiftTo(h,c);g<0&&BigInteger.ZERO.subTo(c,c)}}}}function bnMod(a){var b=nbi();this.abs().divRemTo(a,null,b);this.s<0&&b.compareTo(BigInteger.ZERO)>0&&a.subTo(b,b);return b}function Classic(a){this.m=a}
function cConvert(a){return a.s<0||a.compareTo(this.m)>=0?a.mod(this.m):a}function cRevert(a){return a}function cReduce(a){a.divRemTo(this.m,null,a)}function cMulTo(a,b,c){a.multiplyTo(b,c);this.reduce(c)}function cSqrTo(a,b){a.squareTo(b);this.reduce(b)}Classic.prototype.convert=cConvert;Classic.prototype.revert=cRevert;Classic.prototype.reduce=cReduce;Classic.prototype.mulTo=cMulTo;Classic.prototype.sqrTo=cSqrTo;
function bnpInvDigit(){if(this.t<1)return 0;var a=this[0];if((a&1)==0)return 0;var b=a&3,b=b*(2-(a&15)*b)&15,b=b*(2-(a&255)*b)&255,b=b*(2-((a&65535)*b&65535))&65535,b=b*(2-a*b%this.DV)%this.DV;return b>0?this.DV-b:-b}function Montgomery(a){this.m=a;this.mp=a.invDigit();this.mpl=this.mp&32767;this.mph=this.mp>>15;this.um=(1<<a.DB-15)-1;this.mt2=2*a.t}
function montConvert(a){var b=nbi();a.abs().dlShiftTo(this.m.t,b);b.divRemTo(this.m,null,b);a.s<0&&b.compareTo(BigInteger.ZERO)>0&&this.m.subTo(b,b);return b}function montRevert(a){var b=nbi();a.copyTo(b);this.reduce(b);return b}
function montReduce(a){for(;a.t<=this.mt2;)a[a.t++]=0;for(var b=0;b<this.m.t;++b){var c=a[b]&32767,d=c*this.mpl+((c*this.mph+(a[b]>>15)*this.mpl&this.um)<<15)&a.DM,c=b+this.m.t;for(a[c]=a[c]+this.m.am(0,d,a,b,0,this.m.t);a[c]>=a.DV;){a[c]=a[c]-a.DV;a[++c]++}}a.clamp();a.drShiftTo(this.m.t,a);a.compareTo(this.m)>=0&&a.subTo(this.m,a)}function montSqrTo(a,b){a.squareTo(b);this.reduce(b)}function montMulTo(a,b,c){a.multiplyTo(b,c);this.reduce(c)}Montgomery.prototype.convert=montConvert;
Montgomery.prototype.revert=montRevert;Montgomery.prototype.reduce=montReduce;Montgomery.prototype.mulTo=montMulTo;Montgomery.prototype.sqrTo=montSqrTo;function bnpIsEven(){return(this.t>0?this[0]&1:this.s)==0}function bnpExp(a,b){if(a>4294967295||a<1)return BigInteger.ONE;var c=nbi(),d=nbi(),e=b.convert(this),f=nbits(a)-1;for(e.copyTo(c);--f>=0;){b.sqrTo(c,d);if((a&1<<f)>0)b.mulTo(d,e,c);else var g=c,c=d,d=g}return b.revert(c)}
function bnModPowInt(a,b){var c;c=a<256||b.isEven()?new Classic(b):new Montgomery(b);return this.exp(a,c)}BigInteger.prototype.copyTo=bnpCopyTo;BigInteger.prototype.fromInt=bnpFromInt;BigInteger.prototype.fromString=bnpFromString;BigInteger.prototype.clamp=bnpClamp;BigInteger.prototype.dlShiftTo=bnpDLShiftTo;BigInteger.prototype.drShiftTo=bnpDRShiftTo;BigInteger.prototype.lShiftTo=bnpLShiftTo;BigInteger.prototype.rShiftTo=bnpRShiftTo;BigInteger.prototype.subTo=bnpSubTo;
BigInteger.prototype.multiplyTo=bnpMultiplyTo;BigInteger.prototype.squareTo=bnpSquareTo;BigInteger.prototype.divRemTo=bnpDivRemTo;BigInteger.prototype.invDigit=bnpInvDigit;BigInteger.prototype.isEven=bnpIsEven;BigInteger.prototype.exp=bnpExp;BigInteger.prototype.toString=bnToString;BigInteger.prototype.negate=bnNegate;BigInteger.prototype.abs=bnAbs;BigInteger.prototype.compareTo=bnCompareTo;BigInteger.prototype.bitLength=bnBitLength;BigInteger.prototype.mod=bnMod;BigInteger.prototype.modPowInt=bnModPowInt;
BigInteger.ZERO=nbv(0);BigInteger.ONE=nbv(1);function bnClone(){var a=nbi();this.copyTo(a);return a}function bnIntValue(){if(this.s<0){if(this.t==1)return this[0]-this.DV;if(this.t==0)return-1}else{if(this.t==1)return this[0];if(this.t==0)return 0}return(this[1]&(1<<32-this.DB)-1)<<this.DB|this[0]}function bnByteValue(){return this.t==0?this.s:this[0]<<24>>24}function bnShortValue(){return this.t==0?this.s:this[0]<<16>>16}function bnpChunkSize(a){return Math.floor(Math.LN2*this.DB/Math.log(a))}
function bnSigNum(){return this.s<0?-1:this.t<=0||this.t==1&&this[0]<=0?0:1}function bnpToRadix(a){a==null&&(a=10);if(this.signum()==0||a<2||a>36)return"0";var b=this.chunkSize(a),b=Math.pow(a,b),c=nbv(b),d=nbi(),e=nbi(),f="";for(this.divRemTo(c,d,e);d.signum()>0;){f=(b+e.intValue()).toString(a).substr(1)+f;d.divRemTo(c,d,e)}return e.intValue().toString(a)+f}
function bnpFromRadix(a,b){this.fromInt(0);b==null&&(b=10);for(var c=this.chunkSize(b),d=Math.pow(b,c),e=false,f=0,g=0,h=0;h<a.length;++h){var i=intAt(a,h);if(i<0)a.charAt(h)=="-"&&this.signum()==0&&(e=true);else{g=b*g+i;if(++f>=c){this.dMultiply(d);this.dAddOffset(g,0);g=f=0}}}if(f>0){this.dMultiply(Math.pow(b,f));this.dAddOffset(g,0)}e&&BigInteger.ZERO.subTo(this,this)}
function bnpFromNumber(a,b,c){if("number"==typeof b)if(a<2)this.fromInt(1);else{this.fromNumber(a,c);this.testBit(a-1)||this.bitwiseTo(BigInteger.ONE.shiftLeft(a-1),op_or,this);for(this.isEven()&&this.dAddOffset(1,0);!this.isProbablePrime(b);){this.dAddOffset(2,0);this.bitLength()>a&&this.subTo(BigInteger.ONE.shiftLeft(a-1),this)}}else{var c=[],d=a&7;c.length=(a>>3)+1;b.nextBytes(c);c[0]=d>0?c[0]&(1<<d)-1:0;this.fromString(c,256)}}
function bnToByteArray(){var a=this.t,b=[];b[0]=this.s;var c=this.DB-a*this.DB%8,d,e=0;if(a-- >0){if(c<this.DB&&(d=this[a]>>c)!=(this.s&this.DM)>>c)b[e++]=d|this.s<<this.DB-c;for(;a>=0;){if(c<8){d=(this[a]&(1<<c)-1)<<8-c;d=d|this[--a]>>(c=c+(this.DB-8))}else{d=this[a]>>(c=c-8)&255;if(c<=0){c=c+this.DB;--a}}(d&128)!=0&&(d=d|-256);e==0&&(this.s&128)!=(d&128)&&++e;if(e>0||d!=this.s)b[e++]=d}}return b}function bnEquals(a){return this.compareTo(a)==0}
function bnMin(a){return this.compareTo(a)<0?this:a}function bnMax(a){return this.compareTo(a)>0?this:a}function bnpBitwiseTo(a,b,c){var d,e,f=Math.min(a.t,this.t);for(d=0;d<f;++d)c[d]=b(this[d],a[d]);if(a.t<this.t){e=a.s&this.DM;for(d=f;d<this.t;++d)c[d]=b(this[d],e);c.t=this.t}else{e=this.s&this.DM;for(d=f;d<a.t;++d)c[d]=b(e,a[d]);c.t=a.t}c.s=b(this.s,a.s);c.clamp()}function op_and(a,b){return a&b}function bnAnd(a){var b=nbi();this.bitwiseTo(a,op_and,b);return b}function op_or(a,b){return a|b}
function bnOr(a){var b=nbi();this.bitwiseTo(a,op_or,b);return b}function op_xor(a,b){return a^b}function bnXor(a){var b=nbi();this.bitwiseTo(a,op_xor,b);return b}function op_andnot(a,b){return a&~b}function bnAndNot(a){var b=nbi();this.bitwiseTo(a,op_andnot,b);return b}function bnNot(){for(var a=nbi(),b=0;b<this.t;++b)a[b]=this.DM&~this[b];a.t=this.t;a.s=~this.s;return a}function bnShiftLeft(a){var b=nbi();a<0?this.rShiftTo(-a,b):this.lShiftTo(a,b);return b}
function bnShiftRight(a){var b=nbi();a<0?this.lShiftTo(-a,b):this.rShiftTo(a,b);return b}function lbit(a){if(a==0)return-1;var b=0;if((a&65535)==0){a=a>>16;b=b+16}if((a&255)==0){a=a>>8;b=b+8}if((a&15)==0){a=a>>4;b=b+4}if((a&3)==0){a=a>>2;b=b+2}(a&1)==0&&++b;return b}function bnGetLowestSetBit(){for(var a=0;a<this.t;++a)if(this[a]!=0)return a*this.DB+lbit(this[a]);return this.s<0?this.t*this.DB:-1}function cbit(a){for(var b=0;a!=0;){a=a&a-1;++b}return b}
function bnBitCount(){for(var a=0,b=this.s&this.DM,c=0;c<this.t;++c)a=a+cbit(this[c]^b);return a}function bnTestBit(a){var b=Math.floor(a/this.DB);return b>=this.t?this.s!=0:(this[b]&1<<a%this.DB)!=0}function bnpChangeBit(a,b){var c=BigInteger.ONE.shiftLeft(a);this.bitwiseTo(c,b,c);return c}function bnSetBit(a){return this.changeBit(a,op_or)}function bnClearBit(a){return this.changeBit(a,op_andnot)}function bnFlipBit(a){return this.changeBit(a,op_xor)}
function bnpAddTo(a,b){for(var c=0,d=0,e=Math.min(a.t,this.t);c<e;){d=d+(this[c]+a[c]);b[c++]=d&this.DM;d=d>>this.DB}if(a.t<this.t){for(d=d+a.s;c<this.t;){d=d+this[c];b[c++]=d&this.DM;d=d>>this.DB}d=d+this.s}else{for(d=d+this.s;c<a.t;){d=d+a[c];b[c++]=d&this.DM;d=d>>this.DB}d=d+a.s}b.s=d<0?-1:0;d>0?b[c++]=d:d<-1&&(b[c++]=this.DV+d);b.t=c;b.clamp()}function bnAdd(a){var b=nbi();this.addTo(a,b);return b}function bnSubtract(a){var b=nbi();this.subTo(a,b);return b}
function bnMultiply(a){var b=nbi();this.multiplyTo(a,b);return b}function bnSquare(){var a=nbi();this.squareTo(a);return a}function bnDivide(a){var b=nbi();this.divRemTo(a,b,null);return b}function bnRemainder(a){var b=nbi();this.divRemTo(a,null,b);return b}function bnDivideAndRemainder(a){var b=nbi(),c=nbi();this.divRemTo(a,b,c);return[b,c]}function bnpDMultiply(a){this[this.t]=this.am(0,a-1,this,0,0,this.t);++this.t;this.clamp()}
function bnpDAddOffset(a,b){if(a!=0){for(;this.t<=b;)this[this.t++]=0;for(this[b]=this[b]+a;this[b]>=this.DV;){this[b]=this[b]-this.DV;++b>=this.t&&(this[this.t++]=0);++this[b]}}}function NullExp(){}function nNop(a){return a}function nMulTo(a,b,c){a.multiplyTo(b,c)}function nSqrTo(a,b){a.squareTo(b)}NullExp.prototype.convert=nNop;NullExp.prototype.revert=nNop;NullExp.prototype.mulTo=nMulTo;NullExp.prototype.sqrTo=nSqrTo;function bnPow(a){return this.exp(a,new NullExp)}
function bnpMultiplyLowerTo(a,b,c){var d=Math.min(this.t+a.t,b);c.s=0;for(c.t=d;d>0;)c[--d]=0;var e;for(e=c.t-this.t;d<e;++d)c[d+this.t]=this.am(0,a[d],c,d,0,this.t);for(e=Math.min(a.t,b);d<e;++d)this.am(0,a[d],c,d,0,b-d);c.clamp()}function bnpMultiplyUpperTo(a,b,c){--b;var d=c.t=this.t+a.t-b;for(c.s=0;--d>=0;)c[d]=0;for(d=Math.max(b-this.t,0);d<a.t;++d)c[this.t+d-b]=this.am(b-d,a[d],c,0,0,this.t+d-b);c.clamp();c.drShiftTo(1,c)}
function Barrett(a){this.r2=nbi();this.q3=nbi();BigInteger.ONE.dlShiftTo(2*a.t,this.r2);this.mu=this.r2.divide(a);this.m=a}function barrettConvert(a){if(a.s<0||a.t>2*this.m.t)return a.mod(this.m);if(a.compareTo(this.m)<0)return a;var b=nbi();a.copyTo(b);this.reduce(b);return b}function barrettRevert(a){return a}
function barrettReduce(a){a.drShiftTo(this.m.t-1,this.r2);if(a.t>this.m.t+1){a.t=this.m.t+1;a.clamp()}this.mu.multiplyUpperTo(this.r2,this.m.t+1,this.q3);for(this.m.multiplyLowerTo(this.q3,this.m.t+1,this.r2);a.compareTo(this.r2)<0;)a.dAddOffset(1,this.m.t+1);for(a.subTo(this.r2,a);a.compareTo(this.m)>=0;)a.subTo(this.m,a)}function barrettSqrTo(a,b){a.squareTo(b);this.reduce(b)}function barrettMulTo(a,b,c){a.multiplyTo(b,c);this.reduce(c)}Barrett.prototype.convert=barrettConvert;
Barrett.prototype.revert=barrettRevert;Barrett.prototype.reduce=barrettReduce;Barrett.prototype.mulTo=barrettMulTo;Barrett.prototype.sqrTo=barrettSqrTo;
function bnModPow(a,b){var c=a.bitLength(),d,e=nbv(1),f;if(c<=0)return e;d=c<18?1:c<48?3:c<144?4:c<768?5:6;f=c<8?new Classic(b):b.isEven()?new Barrett(b):new Montgomery(b);var g=[],h=3,i=d-1,m=(1<<d)-1;g[1]=f.convert(this);if(d>1){c=nbi();for(f.sqrTo(g[1],c);h<=m;){g[h]=nbi();f.mulTo(c,g[h-2],g[h]);h=h+2}}for(var l=a.t-1,k,n=true,j=nbi(),c=nbits(a[l])-1;l>=0;){if(c>=i)k=a[l]>>c-i&m;else{k=(a[l]&(1<<c+1)-1)<<i-c;l>0&&(k=k|a[l-1]>>this.DB+c-i)}for(h=d;(k&1)==0;){k=k>>1;--h}if((c=c-h)<0){c=c+this.DB;
--l}if(n){g[k].copyTo(e);n=false}else{for(;h>1;){f.sqrTo(e,j);f.sqrTo(j,e);h=h-2}if(h>0)f.sqrTo(e,j);else{h=e;e=j;j=h}f.mulTo(j,g[k],e)}for(;l>=0&&(a[l]&1<<c)==0;){f.sqrTo(e,j);h=e;e=j;j=h;if(--c<0){c=this.DB-1;--l}}}return f.revert(e)}
function bnGCD(a){var b=this.s<0?this.negate():this.clone(),a=a.s<0?a.negate():a.clone();if(b.compareTo(a)<0)var c=b,b=a,a=c;var c=b.getLowestSetBit(),d=a.getLowestSetBit();if(d<0)return b;c<d&&(d=c);if(d>0){b.rShiftTo(d,b);a.rShiftTo(d,a)}for(;b.signum()>0;){(c=b.getLowestSetBit())>0&&b.rShiftTo(c,b);(c=a.getLowestSetBit())>0&&a.rShiftTo(c,a);if(b.compareTo(a)>=0){b.subTo(a,b);b.rShiftTo(1,b)}else{a.subTo(b,a);a.rShiftTo(1,a)}}d>0&&a.lShiftTo(d,a);return a}
function bnpModInt(a){if(a<=0)return 0;var b=this.DV%a,c=this.s<0?a-1:0;if(this.t>0)if(b==0)c=this[0]%a;else for(var d=this.t-1;d>=0;--d)c=(b*c+this[d])%a;return c}
function bnModInverse(a){var b=a.isEven();if(this.isEven()&&b||a.signum()==0)return BigInteger.ZERO;for(var c=a.clone(),d=this.clone(),e=nbv(1),f=nbv(0),g=nbv(0),h=nbv(1);c.signum()!=0;){for(;c.isEven();){c.rShiftTo(1,c);if(b){if(!e.isEven()||!f.isEven()){e.addTo(this,e);f.subTo(a,f)}e.rShiftTo(1,e)}else f.isEven()||f.subTo(a,f);f.rShiftTo(1,f)}for(;d.isEven();){d.rShiftTo(1,d);if(b){if(!g.isEven()||!h.isEven()){g.addTo(this,g);h.subTo(a,h)}g.rShiftTo(1,g)}else h.isEven()||h.subTo(a,h);h.rShiftTo(1,
h)}if(c.compareTo(d)>=0){c.subTo(d,c);b&&e.subTo(g,e);f.subTo(h,f)}else{d.subTo(c,d);b&&g.subTo(e,g);h.subTo(f,h)}}if(d.compareTo(BigInteger.ONE)!=0)return BigInteger.ZERO;if(h.compareTo(a)>=0)return h.subtract(a);if(h.signum()<0)h.addTo(a,h);else return h;return h.signum()<0?h.add(a):h}
var lowprimes=[2,3,5,7,11,13,17,19,23,29,31,37,41,43,47,53,59,61,67,71,73,79,83,89,97,101,103,107,109,113,127,131,137,139,149,151,157,163,167,173,179,181,191,193,197,199,211,223,227,229,233,239,241,251,257,263,269,271,277,281,283,293,307,311,313,317,331,337,347,349,353,359,367,373,379,383,389,397,401,409,419,421,431,433,439,443,449,457,461,463,467,479,487,491,499,503,509,521,523,541,547,557,563,569,571,577,587,593,599,601,607,613,617,619,631,641,643,647,653,659,661,673,677,683,691,701,709,719,727,
733,739,743,751,757,761,769,773,787,797,809,811,821,823,827,829,839,853,857,859,863,877,881,883,887,907,911,919,929,937,941,947,953,967,971,977,983,991,997],lplim=67108864/lowprimes[lowprimes.length-1];
function bnIsProbablePrime(a){var b,c=this.abs();if(c.t==1&&c[0]<=lowprimes[lowprimes.length-1]){for(b=0;b<lowprimes.length;++b)if(c[0]==lowprimes[b])return true;return false}if(c.isEven())return false;for(b=1;b<lowprimes.length;){for(var d=lowprimes[b],e=b+1;e<lowprimes.length&&d<lplim;)d=d*lowprimes[e++];for(d=c.modInt(d);b<e;)if(d%lowprimes[b++]==0)return false}return c.millerRabin(a)}
function bnpMillerRabin(a){var b=this.subtract(BigInteger.ONE),c=b.getLowestSetBit();if(c<=0)return false;var d=b.shiftRight(c),a=a+1>>1;if(a>lowprimes.length)a=lowprimes.length;for(var e=nbi(),f=0;f<a;++f){e.fromInt(lowprimes[Math.floor(Math.random()*lowprimes.length)]);var g=e.modPow(d,this);if(g.compareTo(BigInteger.ONE)!=0&&g.compareTo(b)!=0){for(var h=1;h++<c&&g.compareTo(b)!=0;){g=g.modPowInt(2,this);if(g.compareTo(BigInteger.ONE)==0)return false}if(g.compareTo(b)!=0)return false}}return true}
BigInteger.prototype.chunkSize=bnpChunkSize;BigInteger.prototype.toRadix=bnpToRadix;BigInteger.prototype.fromRadix=bnpFromRadix;BigInteger.prototype.fromNumber=bnpFromNumber;BigInteger.prototype.bitwiseTo=bnpBitwiseTo;BigInteger.prototype.changeBit=bnpChangeBit;BigInteger.prototype.addTo=bnpAddTo;BigInteger.prototype.dMultiply=bnpDMultiply;BigInteger.prototype.dAddOffset=bnpDAddOffset;BigInteger.prototype.multiplyLowerTo=bnpMultiplyLowerTo;BigInteger.prototype.multiplyUpperTo=bnpMultiplyUpperTo;
BigInteger.prototype.modInt=bnpModInt;BigInteger.prototype.millerRabin=bnpMillerRabin;BigInteger.prototype.clone=bnClone;BigInteger.prototype.intValue=bnIntValue;BigInteger.prototype.byteValue=bnByteValue;BigInteger.prototype.shortValue=bnShortValue;BigInteger.prototype.signum=bnSigNum;BigInteger.prototype.toByteArray=bnToByteArray;BigInteger.prototype.equals=bnEquals;BigInteger.prototype.min=bnMin;BigInteger.prototype.max=bnMax;BigInteger.prototype.and=bnAnd;BigInteger.prototype.or=bnOr;
BigInteger.prototype.xor=bnXor;BigInteger.prototype.andNot=bnAndNot;BigInteger.prototype.not=bnNot;BigInteger.prototype.shiftLeft=bnShiftLeft;BigInteger.prototype.shiftRight=bnShiftRight;BigInteger.prototype.getLowestSetBit=bnGetLowestSetBit;BigInteger.prototype.bitCount=bnBitCount;BigInteger.prototype.testBit=bnTestBit;BigInteger.prototype.setBit=bnSetBit;BigInteger.prototype.clearBit=bnClearBit;BigInteger.prototype.flipBit=bnFlipBit;BigInteger.prototype.add=bnAdd;BigInteger.prototype.subtract=bnSubtract;
BigInteger.prototype.multiply=bnMultiply;BigInteger.prototype.divide=bnDivide;BigInteger.prototype.remainder=bnRemainder;BigInteger.prototype.divideAndRemainder=bnDivideAndRemainder;BigInteger.prototype.modPow=bnModPow;BigInteger.prototype.modInverse=bnModInverse;BigInteger.prototype.pow=bnPow;BigInteger.prototype.gcd=bnGCD;BigInteger.prototype.isProbablePrime=bnIsProbablePrime;BigInteger.prototype.square=bnSquare;function Arcfour(){this.j=this.i=0;this.S=[]}
function ARC4init(a){var b,c,d;for(b=0;b<256;++b)this.S[b]=b;for(b=c=0;b<256;++b){c=c+this.S[b]+a[b%a.length]&255;d=this.S[b];this.S[b]=this.S[c];this.S[c]=d}this.j=this.i=0}function ARC4next(){var a;this.i=this.i+1&255;this.j=this.j+this.S[this.i]&255;a=this.S[this.i];this.S[this.i]=this.S[this.j];this.S[this.j]=a;return this.S[a+this.S[this.i]&255]}Arcfour.prototype.init=ARC4init;Arcfour.prototype.next=ARC4next;function prng_newstate(){return new Arcfour}var rng_psize=256,rng_state,rng_pool,rng_pptr;
function rng_seed_int(a){rng_pool[rng_pptr++]^=a&255;rng_pool[rng_pptr++]^=a>>8&255;rng_pool[rng_pptr++]^=a>>16&255;rng_pool[rng_pptr++]^=a>>24&255;rng_pptr>=rng_psize&&(rng_pptr=rng_pptr-rng_psize)}function rng_seed_time(){rng_seed_int((new Date).getTime())}
if(null==rng_pool){rng_pool=[];rng_pptr=0;var t;if("Netscape"==navigator.appName&&"5">navigator.appVersion&&window.crypto){var z=window.crypto.random(32);for(t=0;t<z.length;++t)rng_pool[rng_pptr++]=z.charCodeAt(t)&255}for(;rng_pptr<rng_psize;)t=Math.floor(65536*Math.random()),rng_pool[rng_pptr++]=t>>>8,rng_pool[rng_pptr++]=t&255;rng_pptr=0;rng_seed_time()}
function rng_get_byte(){if(rng_state==null){rng_seed_time();rng_state=prng_newstate();rng_state.init(rng_pool);for(rng_pptr=0;rng_pptr<rng_pool.length;++rng_pptr)rng_pool[rng_pptr]=0;rng_pptr=0}return rng_state.next()}function rng_get_bytes(a){var b;for(b=0;b<a.length;++b)a[b]=rng_get_byte()}function SecureRandom(){}SecureRandom.prototype.nextBytes=rng_get_bytes;function parseBigInt(a,b){return new BigInteger(a,b)}
function linebrk(a,b){for(var c="",d=0;d+b<a.length;){c=c+(a.substring(d,d+b)+"\n");d=d+b}return c+a.substring(d,a.length)}function byte2Hex(a){return a<16?"0"+a.toString(16):a.toString(16)}
function pkcs1pad2(a,b){if(b<a.length+11){alert("Message too long for RSA");return null}for(var c=[],d=a.length-1;d>=0&&b>0;){var e=a.charCodeAt(d--);if(e<128)c[--b]=e;else if(e>127&&e<2048){c[--b]=e&63|128;c[--b]=e>>6|192}else{c[--b]=e&63|128;c[--b]=e>>6&63|128;c[--b]=e>>12|224}}c[--b]=0;d=new SecureRandom;for(e=[];b>2;){for(e[0]=0;e[0]==0;)d.nextBytes(e);c[--b]=e[0]}c[--b]=2;c[--b]=0;return new BigInteger(c)}
function RSAKey(){this.n=null;this.e=0;this.coeff=this.dmq1=this.dmp1=this.q=this.p=this.d=null}function RSASetPublic(a,b){if(a!=null&&b!=null&&a.length>0&&b.length>0){this.n=parseBigInt(a,16);this.e=parseInt(b,16)}else alert("Invalid RSA public key")}function RSADoPublic(a){return a.modPowInt(this.e,this.n)}function RSAEncrypt(a){a=pkcs1pad2(a,this.n.bitLength()+7>>3);if(a==null)return null;a=this.doPublic(a);if(a==null)return null;a=a.toString(16);return(a.length&1)==0?a:"0"+a}
RSAKey.prototype.doPublic=RSADoPublic;RSAKey.prototype.setPublic=RSASetPublic;RSAKey.prototype.encrypt=RSAEncrypt;var b64map="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",b64pad="=";
function hex2b64(a){var b,c,d="";for(b=0;b+3<=a.length;b=b+3){c=parseInt(a.substring(b,b+3),16);d=d+(b64map.charAt(c>>6)+b64map.charAt(c&63))}if(b+1==a.length){c=parseInt(a.substring(b,b+1),16);d=d+b64map.charAt(c<<2)}else if(b+2==a.length){c=parseInt(a.substring(b,b+2),16);d=d+(b64map.charAt(c>>2)+b64map.charAt((c&3)<<4))}for(;(d.length&3)>0;)d=d+b64pad;return d}
function b64tohex(a){var b="",c,d=0,e;for(c=0;c<a.length;++c){if(a.charAt(c)==b64pad)break;v=b64map.indexOf(a.charAt(c));if(!(v<0))if(d==0){b=b+int2char(v>>2);e=v&3;d=1}else if(d==1){b=b+int2char(e<<2|v>>4);e=v&15;d=2}else if(d==2){b=b+int2char(e);b=b+int2char(v>>2);e=v&3;d=3}else{b=b+int2char(e<<2|v>>4);b=b+int2char(v&15);d=0}}d==1&&(b=b+int2char(e<<2));return b}function b64toBA(a){var a=b64tohex(a),b,c=[];for(b=0;2*b<a.length;++b)c[b]=parseInt(a.substring(2*b,2*b+2),16);return c};
