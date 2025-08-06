Imports Newtonsoft.Json.Linq
Imports CrystalDecisions.CrystalReports.Engine
Imports Newtonsoft.Json
Imports System.Data
Imports System.IO
Public Class frmrunrpt
    Dim api As New ApiClass()
    Public myrunno As String = ""
    Private Sub frmrunrpt_Load(sender As Object, e As EventArgs) Handles MyBase.Load

        Try
            '{"no", "P25000002"}
            Dim postData As New Dictionary(Of String, String) From {
                    {"shiftid", "2"}
                }
            Dim mydata = api.post_request("PartialRpt/genReport", postData)
            'Dim jsonObject As JObject = DirectCast(mydata, JObject)

            'Dim jrunlist As JArray = DirectCast(mydata.Item("runlist"), JArray)

            Dim jrundata As JArray = DirectCast(mydata.Item("data"), JArray)


            'Dim runlist As DataTable = JArrayToDataTable(jrunlist, "runlist")
            'runlist.TableName = "runlist"
            Dim rundata As DataTable = JArrayToDataTable(jrundata, "rpt_tbl")

            For Each col As DataColumn In rundata.Columns
                Debug.WriteLine(col.ColumnName & " : " & col.DataType.ToString())
            Next

            Dim ds As New DataSet()
            ' ds.Tables.Add(runlist)
            ds.Tables.Add(rundata)

            Dim report As New ReportDocument()
            report.Load("report/test.rpt")
            report.SetDataSource(ds)
            CrystalReportViewer1.ReportSource = report
        Catch ex As Exception
            Debug.WriteLine(ex)
        End Try




    End Sub
End Class