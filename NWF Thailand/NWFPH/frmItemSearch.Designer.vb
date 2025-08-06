<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()>
Partial Class frmItemSearch
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()>
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()>
    Private Sub InitializeComponent()
        Me.ItemSearchData = New System.Windows.Forms.DataGridView()
        Me.ItemKey = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.LotNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.BinNo = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.QtyAvailable = New System.Windows.Forms.DataGridViewTextBoxColumn()
        Me.PackSize = New System.Windows.Forms.DataGridViewTextBoxColumn()
        CType(Me.ItemSearchData, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'ItemSearchData
        '
        Me.ItemSearchData.AllowUserToAddRows = False
        Me.ItemSearchData.AllowUserToDeleteRows = False
        Me.ItemSearchData.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.ItemSearchData.Columns.AddRange(New System.Windows.Forms.DataGridViewColumn() {Me.ItemKey, Me.LotNo, Me.BinNo, Me.QtyAvailable, Me.PackSize})
        Me.ItemSearchData.Dock = System.Windows.Forms.DockStyle.Fill
        Me.ItemSearchData.Location = New System.Drawing.Point(0, 0)
        Me.ItemSearchData.Margin = New System.Windows.Forms.Padding(8, 9, 8, 9)
        Me.ItemSearchData.Name = "ItemSearchData"
        Me.ItemSearchData.ReadOnly = True
        Me.ItemSearchData.RowTemplate.Height = 30
        Me.ItemSearchData.Size = New System.Drawing.Size(2533, 1061)
        Me.ItemSearchData.TabIndex = 1
        '
        'ItemKey
        '
        Me.ItemKey.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.ItemKey.HeaderText = "Item Key"
        Me.ItemKey.Name = "ItemKey"
        Me.ItemKey.ReadOnly = True
        '
        'LotNo
        '
        Me.LotNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.LotNo.HeaderText = "Lot No"
        Me.LotNo.Name = "LotNo"
        Me.LotNo.ReadOnly = True
        '
        'BinNo
        '
        Me.BinNo.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.BinNo.HeaderText = "Bin No"
        Me.BinNo.Name = "BinNo"
        Me.BinNo.ReadOnly = True
        '
        'QtyAvailable
        '
        Me.QtyAvailable.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.QtyAvailable.HeaderText = "Qty Available"
        Me.QtyAvailable.Name = "QtyAvailable"
        Me.QtyAvailable.ReadOnly = True
        '
        'PackSize
        '
        Me.PackSize.AutoSizeMode = System.Windows.Forms.DataGridViewAutoSizeColumnMode.Fill
        Me.PackSize.HeaderText = "Pack Size"
        Me.PackSize.Name = "PackSize"
        Me.PackSize.ReadOnly = True
        '
        'frmItemSearch
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(19.0!, 37.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(2533, 1061)
        Me.Controls.Add(Me.ItemSearchData)
        Me.Font = New System.Drawing.Font("Microsoft Sans Serif", 24.0!)
        Me.FormBorderStyle = System.Windows.Forms.FormBorderStyle.FixedToolWindow
        Me.Margin = New System.Windows.Forms.Padding(10, 9, 10, 9)
        Me.Name = "frmItemSearch"
        Me.Text = "ITEM SEARCH"
        CType(Me.ItemSearchData, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

    Friend WithEvents ItemSearchData As DataGridView
    Friend WithEvents ItemKey As DataGridViewTextBoxColumn
    Friend WithEvents LotNo As DataGridViewTextBoxColumn
    Friend WithEvents BinNo As DataGridViewTextBoxColumn
    Friend WithEvents QtyAvailable As DataGridViewTextBoxColumn
    Friend WithEvents PackSize As DataGridViewTextBoxColumn
End Class
