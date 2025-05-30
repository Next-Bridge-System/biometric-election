var template_1 = "";
var templates_2 = window.savedFingerTemplates || [];
var result_1 = "";
var result_2 = "";

const failedAttempts = 0

function SuccessFunc1(result) {
    try {
        if (result.ErrorCode == 0) {
            if (result != null && result.BMPBase64.length > 0) {
                document.getElementById('FPImage1').src = "data:image/bmp;base64," + result.BMPBase64;
            }
            template_1 = result.TemplateBase64;
            result_1 = result;

            matchScoreLoop(succMatch, failureFunc);
        }
        else {
            swal.fire({
                title: 'Session Out',
                text: 'Please click the button to restart the voting process.',
                icon: 'error',
            });
            showStep(0);
        }
    } catch (error) {
        console.log(error)
    }
}

function ErrorFunc(status) {
    swal.fire({
        title: 'Device Scanner Turned Off',
        text: 'Please click the button to restart the scanning process.',
        icon: 'error',
    });
}

function CallSGIFPGetData(successCall, failCall) {

    var uri = "https://localhost:8443/SGIFPCapture";
    var XML_HTTP = new XMLHttpRequest();
    XML_HTTP.onreadystatechange = function () {
        if (XML_HTTP.readyState == 4 && XML_HTTP.status == 200) {
            FP_OBJECT = JSON.parse(XML_HTTP.responseText);
            successCall(FP_OBJECT);
        }
        else if (XML_HTTP.status == 404) {
            failCall(XML_HTTP.status)
        }
    }

    XML_HTTP.onerror = function () {
        failCall(XML_HTTP.status);
    }

    var params = "Timeout=" + "30000";
    params += "&Quality=" + "50";
    // params += "&licstr=" + encodeURIComponent(secugen_lic);
    params += "&templateFormat=" + "ISO";
    XML_HTTP.open("POST", uri, true);
    XML_HTTP.send(params);
}

function matchScoreLoop(succFunction, failFunction) {
    if (template_1 === "" || templates_2.length === 0) {
        alert("Please scan a fingerprint first, and ensure templates are loaded.");
        return;
    }

    let matched = false;
    let currentScore = 0;
    let matchedId = null;
    let idQuality = 100;

    for (let i = 0; i < templates_2.length; i++) {
        const uri = "https://localhost:8443/SGIMatchScore";
        const params =
            "template1=" + encodeURIComponent(template_1) +
            "&template2=" + encodeURIComponent(templates_2[i].template) +
            "&templateFormat=ISO";

        const request = new XMLHttpRequest();
        request.open("POST", uri, false); // synchronous for simplicity
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.send(params);

        if (request.status === 200) {
            const response = JSON.parse(request.responseText);
            currentScore = response.MatchingScore;

            if (response.ErrorCode === 0 && currentScore >= idQuality) {
                matched = true;
                matchedId = templates_2[i].id;
                response.matchedId = matchedId;

                fingerName = templates_2[i].finger_name;
                response.fingerName = fingerName;

                succFunction(response);
                break;
            }
        } else if (request.status === 404) {
            failFunction(request.status);
            return;
        }
    }

    if (!matched) {

        swal.fire({
            title: 'NOT MATCHED!',
            text: 'No fingerprint matched out of ' + templates_2.length,
            icon: 'error',
        });
    }
}

function succMatch(result) {
    if (result.ErrorCode == 0) {
        var idQuality = 100;
        if (result.MatchingScore >= idQuality) {
            showStep(3);
            onSuccessFingerPrintVerification();

            // swal.fire({
            //     title: 'Finger Verified! Matched Finger: ' + result.fingerName,
            //     text: 'The fingerprint has been successfully verified.',
            //     icon: 'success',
            // });

            // alert("MATCHED! Score: " + result.MatchingScore + ", Matched ID: " + result.fingerName);
            // document.getElementById("matched_biometric_id").value = result.matchedId;
        } else {
            alert("NOT MATCHED! Score: " + result.MatchingScore);
        }
    } else {
        alert("Error Scanning Fingerprint. ErrorCode = " + result.ErrorCode);
    }
}


function failureFunc(error) {
    alert("On Match Process, failure has been called");
}
