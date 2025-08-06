Imports Newtonsoft.Json.Linq

Public Class frmItemSearch
    Dim api As New ApiClass()
    Public myrunno As String = ""
    Public itemkey_str As String = ""
    Public lotno_str As String = ""
    Public batch_str As String = ""
    Private Sub frmItemSearch_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim postData As New Dictionary(Of String, String) From {
            {"runno", myrunno},
            {"batch", myrunno},
            {"itemkey", myrunno},
            {"lotno", myrunno}
        }
        Dim mydata = api.post_request("PartialPick/itemsearch", postData)

        Dim jsonObject As JObject = DirectCast(mydata, JObject)

        If jsonObject.ContainsKey("itemdata") Then
            Dim dta As JArray = jsonObject("itemdata")
            For Each item As JToken In dta
                'Debug.WriteLine(item) runno, batchno, formulaid, batchsize, runid
                ItemSearchData.Rows.Add({
                      item("ItemKey"),
                      item("LotNo"),
                      item("BinNo"),
                      item("QtyAvailable"),
                      item("PackSize")
                  })

            Next

        End If
    End Sub
End Class