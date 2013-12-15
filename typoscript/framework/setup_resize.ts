#--------------------------------------------
# Display Resize Controls
#--------------------------------------------
lib.resize = COA
lib.resize {
	10 = HTML
	10.value = <span> - 
	
	20 = TEXT
	20 {
		wrap = |&nbsp;
		value = A
		typolink{
			parameter.data = page:uid
			ATagParams = class="small"
			additionalParams = &S=S
		}
	}


	30 = TEXT
	30 {
		wrap = |&nbsp;
		value = A
		typolink{
			parameter.data = page:uid
			additionalParams = &S=M
		}	
	}
	
	40 = TEXT
	40 {
		wrap = |&nbsp;
		value = A
		typolink{
			parameter.data = page:uid
			ATagParams = class="large"
			additionalParams = &S=L
		}
	}
	
	50 = HTML
	50.value = + </span>
}



# -----------------------------------
# Process Size Parameter
# -----------------------------------
[globalVar = GP:S = S]
page.headerData.100 = TEXT
page.headerData.100.value = <style type="text/css"> body {font-size: 50%;}</style>

[globalVar = GP:S = M]
page.headerData.100 = TEXT
page.headerData.100.value = <style type="text/css"> body {font-size: 75%;}</style>

[globalVar = GP:S = L]
page.headerData.100 = TEXT
page.headerData.100.value = <style type="text/css"> body {font-size: 100%;}</style>
[global]
