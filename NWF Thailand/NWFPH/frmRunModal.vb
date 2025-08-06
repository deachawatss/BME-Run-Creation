Imports System.Data.SqlClient
Imports MySql.Data.MySqlClient
Imports Newtonsoft.Json
Imports System.Web.Script.Serialization
Imports Newtonsoft.Json.Linq

Public Class frmRunModal
    Private Sub frmRunModal_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Dim mdb As New MySQL()
        Dim query = "
            Select 
                    tbl_runhrd.runno,
                    tbl_runhrd.formulaid,
                    tbl_runhrd.batchsize,
                    count(*) as tbatch,
                    group_concat(tbl_rundata.batchno) as batchlist
                    from tbl_runhrd
                    inner join tbl_rundata on tbl_rundata.runid = tbl_runhrd.runid
                        where tbl_runhrd.statflag = 'N'
                    group by tbl_runhrd.runno
                    
        "
        Debug.Write(query)
        Dim userData = mdb.SelectData(query)
        DataGridView1.Rows.Clear()
        Dim dataTable As New DataTable()


        'While (userData.Read())

        For Each item As JObject In userData
            Dim runno As String = item("runno")
            Dim formulaid As String = item("formulaid")
            Dim batchsize As String = item("batchsize")
            Dim tbatch As String = item("tbatch")
            Dim batchlist As String = item("batchlist")

            DataGridView1.Rows.Add(runno, formulaid, batchsize, tbatch, batchlist)
        Next
        'End While



    End Sub

    Private Sub DataGridView1_CellContentDoubleClick(sender As Object, e As DataGridViewCellEventArgs) Handles DataGridView1.CellDoubleClick

        If e.RowIndex >= 0 Then
            Me.Close()
            frmPartialPick.dgvpicked.Rows.Clear()
            frmPartialPick.dgpickedlist.Rows.Clear()
            frmPartialPick.dgvtopick.Rows.Clear()

            ' get the DataGridViewRow object for the clicked row
            Dim row As DataGridViewRow = DataGridView1.Rows(e.RowIndex)
            Dim bmedb As New MsSQL()
            Dim nwfdb As New MySQL()

            ' access the data in the cells of the row
            Dim RunNo As Integer = row.Cells("RunNo").Value
            Dim FormulaID As String = row.Cells("FormulaID").Value
            Dim BatchSize As String = row.Cells("BatchSize").Value
            Dim Batch As String = row.Cells("Batch").Value
            Dim BatchList As String = row.Cells("BatchList").Value
            frmPartialPick.gbatchlist = (row.Cells("BatchList").Value).Replace(",", "','")

            Dim batcharray() As String = Split(row.Cells("BatchList").Value.ToString(), ",")

            'frmPartialPick.batchdic = Nothing
            frmPartialPick.batchdic = New Dictionary(Of String, Integer)()
            For Each kv In batcharray
                Dim key As String = kv.Trim() ' Trim any leading or trailing whitespace
                Dim value As Integer = 0 ' Set the initial value as needed
                frmPartialPick.batchdic.Add(key, value)
            Next


            Dim lmydict As New Dictionary(Of String, String)
            lmydict.Add("RunNo", RunNo)
            lmydict.Add("FormulaID", FormulaID)
            lmydict.Add("BatchSize", BatchSize)
            lmydict.Add("Batch", Batch)
            lmydict.Add("BatchList", BatchList)

            frmPartialPick.setmydata(lmydict)


        End If

    End Sub

    Private Sub BackgroundWorker1_DoWork(sender As Object, e As System.ComponentModel.DoWorkEventArgs) Handles BackgroundWorker1.DoWork
        Dim LoadingForm As New Form()
        LoadingForm.Text = "Loading..."
        LoadingForm.Show()

        ' start the background worker to run the MSSQL query
        BackgroundWorker1.RunWorkerAsync()
    End Sub

End Class