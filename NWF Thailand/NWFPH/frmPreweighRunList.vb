Imports Newtonsoft.Json.Linq
Public Class frmPreweighRunList
    Dim api As New ApiClass()
    Private Sub frmPreweighRunList_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        dgRunNo.Rows.Clear()

        Dim postData As New Dictionary(Of String, String) From {
            {"key1", "value1"}
        }
        Dim mydata = api.post_request("PartialPick/findrun", postData)

        Dim jsonObject As JObject = DirectCast(mydata, JObject)
        Dim dtxa As JArray = jsonObject("rundata")
        Debug.WriteLine(dtxa)
        If jsonObject.ContainsKey("results") Then
            Dim dta As JArray = jsonObject("results")
            'Debug.WriteLine(dta)
            For Each item As JToken In dta
                'Debug.WriteLine(item)
                dgRunNo.Rows.Add({
                      item("runno"),
                      item("formulaid"),
                      item("batchsize"),
                      item("tbatch"),
                      item("batchlist")
                  })

            Next

        End If
    End Sub

    Private Sub dgRunNo_CellDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles dgRunNo.CellDoubleClick
        If e.RowIndex >= 0 Then
            Me.Close()
            frmPreweigh.dgindex = 0
            Dim row As DataGridViewRow = dgRunNo.Rows(e.RowIndex)

            Dim RunNo As String = row.Cells("RunNo").Value
            Dim FormulaID As String = row.Cells("FormulaID").Value
            Dim BatchSize As String = row.Cells("BatchSize").Value
            Dim Batch As String = row.Cells("Batch").Value
            Dim BatchList As String = row.Cells("BatchList").Value
            frmPreweigh.gbatchlist = (row.Cells("BatchList").Value)

            Dim batcharray() As String = Split(row.Cells("BatchList").Value.ToString(), ",")

            'frmPartialPick.batchdic = Nothing
            frmPreweigh.batchdic = New Dictionary(Of String, Integer)()
            For Each kv In batcharray
                Dim key As String = kv.Trim() ' Trim any leading or trailing whitespace
                Dim value As Integer = 0 ' Set the initial value as needed
                frmPreweigh.batchdic.Add(key, value)
            Next

            Dim lmydict As New Dictionary(Of String, String)
            lmydict.Add("RunNo", RunNo)
            lmydict.Add("FormulaID", FormulaID)
            lmydict.Add("BatchSize", BatchSize)
            lmydict.Add("Batch", Batch)
            lmydict.Add("BatchList", BatchList)

            frmPreweigh.setmydata(lmydict)

        End If
    End Sub

End Class