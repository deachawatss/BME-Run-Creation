Imports Newtonsoft.Json.Linq
Public Class frmPreweighBatch
    Dim api As New ApiClass()
    Public myrunno As String = ""
    Private Sub frmPreweighBatch_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim postData As New Dictionary(Of String, String) From {
            {"runno", myrunno}
        }
        Dim mydata = api.post_request("PartialPick/getbatchlist", postData)

        Dim jsonObject As JObject = DirectCast(mydata, JObject)

        If jsonObject.ContainsKey("batchlist") Then
            Dim dta As JArray = jsonObject("batchlist")
            For Each item As JToken In dta
                'Debug.WriteLine(item) runno, batchno, formulaid, batchsize, runid
                dgridBatchList.Rows.Add({
                      item("runno"),
                      item("batchno"),
                      item("formulaid"),
                      item("batchsize"),
                      item("runid")
                  })

            Next

        End If

    End Sub
End Class