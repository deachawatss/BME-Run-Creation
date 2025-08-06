<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class frmMainBeta
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
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
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.Panel1 = New System.Windows.Forms.Panel()
        Me.txtuser = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Panel2 = New System.Windows.Forms.Panel()
        Me.btnInventory = New System.Windows.Forms.Button()
        Me.PictureBox1 = New System.Windows.Forms.PictureBox()
        Me.Button2 = New System.Windows.Forms.Button()
        Me.btnHome = New System.Windows.Forms.Button()
        Me.btnabout = New System.Windows.Forms.Button()
        Me.btnwt = New System.Windows.Forms.Button()
        Me.btnconfig = New System.Windows.Forms.Button()
        Me.btnscan = New System.Windows.Forms.Button()
        Me.PanelContainer = New System.Windows.Forms.Panel()
        Me.lblTitle = New System.Windows.Forms.Label()
        Me.Panel1.SuspendLayout()
        Me.Panel2.SuspendLayout()
        CType(Me.PictureBox1, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'Panel1
        '
        Me.Panel1.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Panel1.AutoSize = True
        Me.Panel1.BackColor = System.Drawing.Color.FromArgb(CType(CType(46, Byte), Integer), CType(CType(48, Byte), Integer), CType(CType(71, Byte), Integer))
        Me.Panel1.Controls.Add(Me.txtuser)
        Me.Panel1.Controls.Add(Me.Label1)
        Me.Panel1.Location = New System.Drawing.Point(-9, -1)
        Me.Panel1.Name = "Panel1"
        Me.Panel1.Size = New System.Drawing.Size(1333, 37)
        Me.Panel1.TabIndex = 0
        '
        'txtuser
        '
        Me.txtuser.AutoSize = True
        Me.txtuser.Font = New System.Drawing.Font("Microsoft Sans Serif", 18.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.txtuser.ForeColor = System.Drawing.Color.FromArgb(CType(CType(249, Byte), Integer), CType(CType(250, Byte), Integer), CType(CType(244, Byte), Integer))
        Me.txtuser.Location = New System.Drawing.Point(157, 7)
        Me.txtuser.Name = "txtuser"
        Me.txtuser.Size = New System.Drawing.Size(0, 29)
        Me.txtuser.TabIndex = 1
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 18.0!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.ForeColor = System.Drawing.Color.FromArgb(CType(CType(249, Byte), Integer), CType(CType(250, Byte), Integer), CType(CType(244, Byte), Integer))
        Me.Label1.Location = New System.Drawing.Point(11, 7)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(157, 29)
        Me.Label1.TabIndex = 0
        Me.Label1.Text = "WELCOME: "
        '
        'Panel2
        '
        Me.Panel2.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left), System.Windows.Forms.AnchorStyles)
        Me.Panel2.BackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.Panel2.Controls.Add(Me.btnInventory)
        Me.Panel2.Controls.Add(Me.PictureBox1)
        Me.Panel2.Controls.Add(Me.Button2)
        Me.Panel2.Controls.Add(Me.btnHome)
        Me.Panel2.Controls.Add(Me.btnabout)
        Me.Panel2.Controls.Add(Me.btnwt)
        Me.Panel2.Controls.Add(Me.btnconfig)
        Me.Panel2.Controls.Add(Me.btnscan)
        Me.Panel2.Location = New System.Drawing.Point(1, 36)
        Me.Panel2.Name = "Panel2"
        Me.Panel2.Size = New System.Drawing.Size(304, 797)
        Me.Panel2.TabIndex = 6
        '
        'btnInventory
        '
        Me.btnInventory.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.btnInventory.AutoSize = True
        Me.btnInventory.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.btnInventory.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.btnInventory.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnInventory.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold)
        Me.btnInventory.Image = Global.NWFTH.My.Resources.Resources.icons8_inventory_69
        Me.btnInventory.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.btnInventory.Location = New System.Drawing.Point(3, 350)
        Me.btnInventory.Name = "btnInventory"
        Me.btnInventory.Size = New System.Drawing.Size(294, 77)
        Me.btnInventory.TabIndex = 8
        Me.btnInventory.Text = "INVENTORY"
        Me.btnInventory.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.btnInventory.UseVisualStyleBackColor = True
        '
        'PictureBox1
        '
        Me.PictureBox1.Image = Global.NWFTH.My.Resources.Resources.Logo
        Me.PictureBox1.Location = New System.Drawing.Point(57, 3)
        Me.PictureBox1.Name = "PictureBox1"
        Me.PictureBox1.Size = New System.Drawing.Size(182, 107)
        Me.PictureBox1.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage
        Me.PictureBox1.TabIndex = 7
        Me.PictureBox1.TabStop = False
        '
        'Button2
        '
        Me.Button2.Anchor = CType(((System.Windows.Forms.AnchorStyles.Bottom Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.Button2.AutoSize = True
        Me.Button2.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.Button2.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.Button2.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.Button2.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold)
        Me.Button2.Image = Global.NWFTH.My.Resources.Resources.icons8_log_out_69
        Me.Button2.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.Button2.Location = New System.Drawing.Point(3, 717)
        Me.Button2.Name = "Button2"
        Me.Button2.Size = New System.Drawing.Size(294, 77)
        Me.Button2.TabIndex = 6
        Me.Button2.Text = "LOG-OUT"
        Me.Button2.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.Button2.UseVisualStyleBackColor = True
        '
        'btnHome
        '
        Me.btnHome.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.btnHome.AutoSize = True
        Me.btnHome.FlatAppearance.BorderColor = System.Drawing.Color.Black
        Me.btnHome.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.btnHome.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.btnHome.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnHome.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold)
        Me.btnHome.Image = Global.NWFTH.My.Resources.Resources.icons8_home_69
        Me.btnHome.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.btnHome.Location = New System.Drawing.Point(3, 113)
        Me.btnHome.Name = "btnHome"
        Me.btnHome.Size = New System.Drawing.Size(294, 77)
        Me.btnHome.TabIndex = 0
        Me.btnHome.Text = "HOME"
        Me.btnHome.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.btnHome.UseVisualStyleBackColor = True
        '
        'btnabout
        '
        Me.btnabout.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.btnabout.AutoSize = True
        Me.btnabout.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.btnabout.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.btnabout.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnabout.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold)
        Me.btnabout.Image = Global.NWFTH.My.Resources.Resources.icons8_about_69
        Me.btnabout.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.btnabout.Location = New System.Drawing.Point(3, 430)
        Me.btnabout.Name = "btnabout"
        Me.btnabout.Size = New System.Drawing.Size(294, 77)
        Me.btnabout.TabIndex = 5
        Me.btnabout.Text = "ABOUT"
        Me.btnabout.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.btnabout.UseVisualStyleBackColor = True
        '
        'btnwt
        '
        Me.btnwt.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.btnwt.AutoSize = True
        Me.btnwt.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.btnwt.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.btnwt.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnwt.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold)
        Me.btnwt.Image = Global.NWFTH.My.Resources.Resources.icons8_weighing_69
        Me.btnwt.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.btnwt.Location = New System.Drawing.Point(3, 191)
        Me.btnwt.Name = "btnwt"
        Me.btnwt.Size = New System.Drawing.Size(294, 77)
        Me.btnwt.TabIndex = 1
        Me.btnwt.Text = "PARTIAL WEIGHING"
        Me.btnwt.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.btnwt.UseVisualStyleBackColor = True
        '
        'btnconfig
        '
        Me.btnconfig.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.btnconfig.AutoSize = True
        Me.btnconfig.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.btnconfig.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.btnconfig.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnconfig.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold)
        Me.btnconfig.Image = Global.NWFTH.My.Resources.Resources.icons8_config_69
        Me.btnconfig.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.btnconfig.Location = New System.Drawing.Point(3, 510)
        Me.btnconfig.Name = "btnconfig"
        Me.btnconfig.Size = New System.Drawing.Size(294, 77)
        Me.btnconfig.TabIndex = 4
        Me.btnconfig.Text = "CONFIGURATION"
        Me.btnconfig.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.btnconfig.UseVisualStyleBackColor = True
        '
        'btnscan
        '
        Me.btnscan.Anchor = CType(((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.btnscan.AutoSize = True
        Me.btnscan.FlatAppearance.MouseDownBackColor = System.Drawing.Color.FromArgb(CType(CType(59, Byte), Integer), CType(CType(186, Byte), Integer), CType(CType(156, Byte), Integer))
        Me.btnscan.FlatAppearance.MouseOverBackColor = System.Drawing.Color.FromArgb(CType(CType(192, Byte), Integer), CType(CType(255, Byte), Integer), CType(CType(192, Byte), Integer))
        Me.btnscan.FlatStyle = System.Windows.Forms.FlatStyle.Flat
        Me.btnscan.Font = New System.Drawing.Font("Microsoft Sans Serif", 14.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.btnscan.Image = Global.NWFTH.My.Resources.Resources.icons8_barcode_69
        Me.btnscan.ImageAlign = System.Drawing.ContentAlignment.MiddleLeft
        Me.btnscan.Location = New System.Drawing.Point(3, 270)
        Me.btnscan.Name = "btnscan"
        Me.btnscan.Size = New System.Drawing.Size(294, 77)
        Me.btnscan.TabIndex = 3
        Me.btnscan.Text = "SCAN TO PRINT"
        Me.btnscan.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        Me.btnscan.UseVisualStyleBackColor = True
        '
        'PanelContainer
        '
        Me.PanelContainer.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.PanelContainer.Location = New System.Drawing.Point(314, 91)
        Me.PanelContainer.Name = "PanelContainer"
        Me.PanelContainer.Size = New System.Drawing.Size(979, 738)
        Me.PanelContainer.TabIndex = 7
        '
        'lblTitle
        '
        Me.lblTitle.Anchor = CType((((System.Windows.Forms.AnchorStyles.Top Or System.Windows.Forms.AnchorStyles.Bottom) _
            Or System.Windows.Forms.AnchorStyles.Left) _
            Or System.Windows.Forms.AnchorStyles.Right), System.Windows.Forms.AnchorStyles)
        Me.lblTitle.AutoSize = True
        Me.lblTitle.Font = New System.Drawing.Font("Microsoft Sans Serif", 20.25!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.lblTitle.Location = New System.Drawing.Point(856, 51)
        Me.lblTitle.Name = "lblTitle"
        Me.lblTitle.Size = New System.Drawing.Size(0, 31)
        Me.lblTitle.TabIndex = 8
        '
        'frmMainBeta
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(96.0!, 96.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Dpi
        Me.AutoSize = True
        Me.BackColor = System.Drawing.Color.FromArgb(CType(CType(112, Byte), Integer), CType(CType(119, Byte), Integer), CType(CType(147, Byte), Integer))
        Me.ClientSize = New System.Drawing.Size(1301, 833)
        Me.Controls.Add(Me.lblTitle)
        Me.Controls.Add(Me.PanelContainer)
        Me.Controls.Add(Me.Panel2)
        Me.Controls.Add(Me.Panel1)
        Me.Name = "frmMainBeta"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "NWF MOBILE"
        Me.WindowState = System.Windows.Forms.FormWindowState.Maximized
        Me.Panel1.ResumeLayout(False)
        Me.Panel1.PerformLayout()
        Me.Panel2.ResumeLayout(False)
        Me.Panel2.PerformLayout()
        CType(Me.PictureBox1, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents Panel1 As Panel
    Friend WithEvents btnwt As Button
    Friend WithEvents btnHome As Button
    Friend WithEvents btnconfig As Button
    Friend WithEvents btnscan As Button
    Friend WithEvents btnabout As Button
    Friend WithEvents Panel2 As Panel
    Friend WithEvents Button2 As Button
    Friend WithEvents PictureBox1 As PictureBox
    Friend WithEvents PanelContainer As Panel
    Friend WithEvents lblTitle As Label
    Friend WithEvents txtuser As Label
    Friend WithEvents Label1 As Label
    Friend WithEvents btnInventory As Button
End Class
